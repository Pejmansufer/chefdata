<?php

class Extendware_EWSphinx_Model_Sphinx extends Extendware_EWCore_Model_Singleton_Abstract
{
	protected $api;
	protected $indexes = array();
	protected $boosterScoreToFields;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getApi() {
		if (!$this->api) {
			if (!@class_exists('SphinxClient')) include $this->mHelper('internal_api')->getLibDir().DS.'sphinxapi.php';
			$this->api = new SphinxClient();
		}
		return $this->api;
	}
	
	protected function getStoreId() {
		return Mage::app()->getStore()->getId();
	}

	protected function getWildcardQueryLength($string) {
		return strlen(str_replace(array('?', '*'), '', $string));
	}
	
	protected function getHost() {
		return $this->mHelper('config')->getHost() ? $this->mHelper('config')->getHost() : '127.0.0.1';
	}
	
	protected function getPort() {
		return $this->mHelper('config')->getPort() > 0 ? $this->mHelper('config')->getPort() : 3307;
	}
	
	protected function search($query, $index, $mode = 4, $operator = 'and', array $fieldWeights, $offset = 1, $depth = 0) {
		$fieldWeights = array_map('intval', $fieldWeights);

		$client = $this->getApi();
		$client->setServer($this->getHost(), $this->getPort());
		$client->setMaxQueryTime(30000);
		$client->setLimits(($offset - 1) * 1000, 1000, 100000);
		$client->setMatchMode($mode);
		$client->setFieldWeights($fieldWeights);
		$client->setSortMode(SPH_SORT_RELEVANCE);
		
		$modifiedQuery = $this->modifyQuery($query, $mode);
		if (!$modifiedQuery) {
			return array();
		}
		
		$result = $client->query($modifiedQuery, $index);
		if ($result === false) {
			if ($depth == 0) {
				$this->restart();
				return $this->search($query, $index, $mode, $operator, $fieldWeights, $offset, $depth + 1);
			}
			Mage::throwException($client->getLastError());
		}
		return $result;
	}
	
	protected function getIndexName($type, $storeId, $class = 'source') {
		if ($type == 'product') return sprintf('%s_ewsphinx_catalog_product_%s', $class, $storeId);
		elseif ($type == 'category') return sprintf('%s_ewsphinx_catalog_category_%s', $class, $storeId);
		elseif ($type == 'page') return sprintf('%s_ewsphinx_page_%s', $class, $storeId);
		Mage::throwException($this->__('Unknown index name type encountered'));
	}
	
	protected function getIndexNames($class = 'source') {
		$indexes = array();
		$types = array('product');
		if ($this->mHelper('config')->isCategoryEnabled()) $types[] = 'category';
		if ($this->mHelper('config')->isPageEnabled()) $types[] = 'page';
		
		foreach ($types as $type) {
			$stores = Mage::app()->getStores();
			foreach ($stores as $store) {
				$index = $this->getIndexName($type, $store->getId(), $class);
				if ($this->mHelper('config')->isEnabled($store->getId())) {
					$indexes[] = $index;
				}
			}
		}
		return $indexes;
	}
	
	public function searchProducts($query, $storeId = null) {
		if ($storeId === null) $storeId = Mage::app()->getStore()->getId();
		$index = $this->getIndexName('product', $storeId, 'source');
		$result = $this->search($query, $index, $this->mHelper('config')->getSearchMode(), $this->mHelper('config')->getSearchOperator(), $this->mHelper('config')->getFieldBoosters());
		return $this->getMatchesFromResults($result);
	}
	
	public function searchCategories($query, $storeId = null) {
		if ($this->mHelper('config')->isCategoryEnabled() === false) return array();
		if ($storeId === null) $storeId = Mage::app()->getStore()->getId();
		$index = $this->getIndexName('category', $storeId, 'source');
		$result = $this->search($query, $index, $this->mHelper('config')->getCategorySearchMode(), $this->mHelper('config')->getCategorySearchOperator(), $this->mHelper('config')->getCategoryFieldBoosters());
		return $this->getMatchesFromResults($result);
	}
	
	public function searchPages($query, $storeId = null) {
		if ($this->mHelper('config')->isPageEnabled() === false) return array();
		if ($storeId === null) $storeId = Mage::app()->getStore()->getId();
		$index = $this->getIndexName('page', $storeId, 'source');
		$result = $this->search($query, $index, $this->mHelper('config')->getPageSearchMode(), $this->mHelper('config')->getPageSearchOperator(), $this->mHelper('config')->getPageFieldBoosters());
		return $this->getMatchesFromResults($result);
	}
	
	protected function getMatchesFromResults($result) {
		if (is_array($result) === false) return array();
		$matches = array();
		if (isset($result['matches'])) {
			foreach ($result['matches'] as $productId => $match) {
				$matches[$productId] = $match['weight'] / 1000;
			}
		}
		return $matches;
	}
	
	protected function modifyQuery($query, $mode) {
		if ($mode != SPH_MATCH_EXTENDED) return $this->cleanQueryString($query);
		if (substr($query, 0, 1) == '/') return substr($this->cleanQueryString($query), 1);
		
		$separator = ' ';
		$operator = $this->mHelper('config')->getSearchOperator();
		if ($operator == 'or') $separator = ' | ';
		elseif ($operator == 'and') $wordSeperator = ' & ';

		$tokens = $this->getQueryTokens($query);
		return implode($separator, $tokens);
	}

	public function getStopWords($storeId = null) {
		if ($storeId === null) $storeId = Mage::app()->getStore()->getId();
		static $cache = array();
		if (isset($cache[$storeId]) === false) {
			$stopwords = Mage::getModel('ewsphinx/stopword')->getCollection();
			if ($storeId > 0) $stopwords->addFieldToFilter('store_id', $storeId);
			$cache[$storeId] = $stopwords->getColumnValues('word');
			foreach ($cache[$storeId] as &$word) {
				$word = mb_convert_case($word, MB_CASE_LOWER, 'UTF-8');
				unset($word);
			}
		}
		
		return $cache[$storeId];
	}
	
	protected function getQueryTokens($query) {
		$wildcardMode = $this->mHelper('config')->getWildcardMode();
		$template = '(%s)';
		if ($wildcardMode == 'prefix') $template = '(%s|*%s)';
		elseif ($wildcardMode == 'suffix') $template = '(%s|%s*)';
		elseif ($wildcardMode == 'both') $template = '(%s|*%s*)';
		
		$tokens = array();
		$stopWords = $this->getStopWords();
		$words = $this->splitQueryIntoWords($query, true);
		foreach ($words as $word) {
			if (strlen($word) < 1) continue;
			if (in_array(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), $stopWords)) $tokens[] = sprintf('(%s)', $word);
			else $tokens[] = sprintf($template, $word, $word);
		}
		return $tokens;
	}

	protected function cleanQueryString($string) {
		return $this->mHelper()->cleanString($string);
	}

	protected function splitQueryIntoWords($string, $useSplitWords = false) {
		$string = $this->cleanQueryString($string);
		if ($useSplitWords === true) return Mage::helper ('core/string')->splitWords($string, true, Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MAX_QUERY_WORDS));
		return explode (' ', $string);
	}
	
	protected function getSearchdBinaryPath() {
		$executable = 'searchd';
		if (Mage::helper('ewcore/environment')->isWindows()) $executable .= '.exe';
		return rtrim($this->mHelper('config')->getBinaryPath(), '/') . '/' . $executable;
	}
	
	protected function getIndexerBinaryPath() {
		$executable = 'indexer';
		if (Mage::helper('ewcore/environment')->isWindows()) $executable .= '.exe';
		return rtrim($this->mHelper('config')->getBinaryPath(), '/') . '/' . $executable;
	}
	
	public function getConfigFilePath() {
		return $this->mHelper('internal_api')->getVarDir() . DS . 'sphinx.conf';
	}
	
	protected function getIndexerCommand($indexes = null) {
		if ($indexes === null) $indexes = array('--all');
		return sprintf('%s --config %s --rotate %s', escapeshellcmd($this->getIndexerBinaryPath()), escapeshellarg($this->getConfigFilePath()), implode(' ', $indexes));
	}
	
	protected function getMergeCommand($sourceIndex, $deltaIndex) {
		return sprintf('%s --config %s --merge %s %s --merge-dst-range deleted 0 0 --rotate', escapeshellcmd($this->getIndexerBinaryPath()), escapeshellarg($this->getConfigFilePath()), escapeshellarg($sourceIndex), escapeshellarg($deltaIndex));
	}
	
	protected function getSearchdCommand() {
		return sprintf('%s --config %s', escapeshellcmd($this->getSearchdBinaryPath()), escapeshellarg($this->getConfigFilePath()));
	}
	
	public function writeConfig() {
		$this->mHelper('config_writer')->write();
		return $this;
	}
	
	protected function doesIndexExist() {
		$files = Mage::helper('ewcore/file')->getFilesInDirectory($this->mHelper('internal_api')->getVarDir('indexes'));
		foreach ($files as $file) {
			$file = basename($file);
			if (strpos($file, $this->getIndexName('product', '', '')) !== false) {
				return true;
			}
		}
		return false;
	}
	
	protected function getLogFile($suffix = '') {
		$directory = Mage::getConfig()->getOptions()->getVarDir() . DS . 'log' . DS . 'sphinx';
		if (Mage::getConfig()->getOptions()->createDirIfNotExists($directory) === false) {
			Mage::throwException($this->__('Could not create directory: %s', $directory));
		}
		if ($suffix) $directory .= DS . $suffix;
		return $directory;
	}
	
	public function prepareMySql() {
		$waitTimeout = $this->mHelper('config')->getMysqlWaitTimeout();
		if ($waitTimeout > 0) {
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$write->query(sprintf('SET SESSION wait_timeout = %s', $waitTimeout));
		}
		
		return $this;
	}
	public function reindex($delta = false, $restart = true) {
		$this->prepareMySql();
		$this->checkConnectionMode();
		$this->writeConfig();
		
		if ($this->isIndexerRunning() === false) {
			$indexes = null;

			if ($delta === true) $indexes = $this->getIndexNames('delta');
			else $indexes = $this->getIndexNames('source');
			
			$this->mHelper('internal_api')->getVarDir('indexes'); // create this directory just in case
			
			$output = $error = null;
			if (empty($indexes) === false) {
				$this->mHelper('lock')->lock('indexer');
				try { Mage::getSingleton('core/resource')->getConnection('core_read')->closeConnection(); }
		    	catch (Exception $e) {}
				$output = @shell_exec($this->getIndexerCommand($indexes));
				
				$this->mHelper('lock')->unlock('indexer');
				$output = implode("\n", $output);
				$result = ($error == 0 or strpos($output, 'rotating indices: succesfully') !== false);
				if (!$result) {
					$this->mHelper('system')->log($this->getIndexerCommand($indexes));
					Mage::throwException($this->__('Indexing error: %s', $output));
				}
			}
			
			if ($delta === true) {
				foreach ($indexes as $deltaIndex) {
					$sourceIndex = str_replace('delta_', 'source_', $deltaIndex);
					$output = $error = null;
					$this->mHelper('lock')->lock('indexer');
					@exec($this->getMergeCommand($sourceIndex, $deltaIndex), $output, $error);
					$this->mHelper('lock')->unlock('indexer');
					$output = implode("\n", $output);
					$result = ($error == 0 or strpos($output, 'rotating indices: succesfully') !== false);
					if (!$result) {
						$this->mHelper('system')->log($this->getMergeCommand($sourceIndex, $deltaIndex));
						Mage::throwException($this->__('Indexing error: %s', $output));
					}
				}
			}
			if ($restart === true) $this->restart();
			Mage::getResourceModel('catalogsearch/fulltext')->resetSearchResults();
		} else {
			Mage::throwException($this->__('Indexer is already running. Please try again later!'));
		}
		
		return $this;
	}

	protected function isRunning($type) {
		$status = false;
		if (Mage::helper('ewcore/environment')->isWindows()) {
			$content = shell_exec('tasklist'); 
			$status = (bool)strpos($content, sprintf('%s.exe', $type));
		} else {
			@exec(sprintf('ps aux | grep %s | grep "/ewsphinx/sphinx.conf"', $type), $output, $error);
			if ($error == 0) {
				foreach ($output as $result) {
					$pos = (strpos($result, $type) > 0 and strpos($result, 'extendware/ewsphinx/sphinx.conf') > 0);
					if ($pos !== false) {
						$status = true;
						break;
					}
				}
			}
		}
		if ($status === false) {
			$status = $this->mHelper('lock')->isLocked($type);
		}
		
		return $status;
	}
	
	protected function isIndexerRunning() {
		$this->checkConnectionMode();
		return $this->isRunning('indexer');
	}
	
	protected function isSearchdRunning() {
		$this->checkConnectionMode();
		return $this->isRunning('searchd');
	}
	
	protected function start() {
		$this->checkConnectionMode();
		Mage::helper('ewsphinx')->checkCommand($this->getSearchdBinaryPath());
		$this->writeConfig();
		
		$this->getLogFile(); // called to ensure log file directory exists
		
		if ($this->doesIndexExist() === false) {
			$this->reindex(false, false);
		}
		
		$output = $error = null;
		@exec($this->getSearchdCommand(), $output, $error);
		Mage::getResourceModel('catalogsearch/fulltext')->resetSearchResults();
		
		if ($error !== 0) {
			$output = implode("\n", $output);
			if (preg_match('/FATAL: no valid indexes/i', $output)) {
				Mage::throwException($this->__('Sphinx failed to start due to invalid indexes: %s', $output));
			} elseif (preg_match('/FATAL: failed to lock pid/i', $output)) {
				Mage::throwException($this->__('Sphinx failed to start due to already locked pid file: %s', $output));
			} elseif (preg_match('/FATAL: no indexes found in/i', $output)) {
				Mage::throwException($this->__('There are no indexes to search. Please ensure you have set the Status to Enabled otherwise sphinx will not be used.', $output));
			} else {
				Mage::throwException($this->__('Sphinx failed to start: %s', $output));
			}
		}
		return $this;
	}
	
	public function restart() {
		$this->checkConnectionMode();
		Mage::helper('ewsphinx')->checkCommand($this->getSearchdBinaryPath());
		$this->stop();
		$this->start();
		return $this;
	}
	
	protected function stop() {
		$this->checkConnectionMode();
		Mage::helper('ewsphinx')->checkCommand($this->getSearchdBinaryPath());
		if ($this->isSearchdRunning() === true) {
			if (Mage::helper('ewcore/environment')->isWindows()) {
				$output = $error = null;
				@exec(sprintf('taskkill /F /IM searchd.exe'), $output, $error);
			} else {
				$output = $error = null;
				@exec(sprintf('/usr/bin/killall -9 searchd > /dev/null'), $output, $error);
			}
		}
		return $this;
    }
    
	protected function checkConnectionMode($mode = 'local', $throwException = true) {
		if ($this->mHelper('config')->getConnectionMode() != $mode) {
			if ($throwException) Mage::throwException($this->__('Cannot call method as connection mode is not %s', $mode));
			return false;
		}
		return true;
	}
}
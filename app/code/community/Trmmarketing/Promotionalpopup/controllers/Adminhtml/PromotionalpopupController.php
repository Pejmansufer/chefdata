<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Adminhtml_PromotionalpopupController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('promotionalpopup/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Pop-up Manager'), Mage::helper('adminhtml')->__('Pop-up Manager'));
		
		return $this;
	}   
	
	
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('promotionalpopup/promotionalpopup')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			

			Mage::register('promotionalpopup_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('promotionalpopup/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			//$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			/*
			$this->getLayout()->getBlock('head')
			->setCanLoadExtJs(true)
			->setCanLoadTinyMce(true)
			->addItem('js','tiny_mce/tiny_mce.js')
			->addItem('js','mage/adminhtml/wysiwyg/tiny_mce/setup.js')
			->addJs('mage/adminhtml/browser.js')
			->addJs('prototype/window.js')
			->addJs('lib/flex.js')
			->addJs('mage/adminhtml/flexuploader.js')
			->addItem('js_css','prototype/windows/themes/default.css')
			->addItem('js_css','prototype/windows/themes/magento.css');
			*/
			 if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            } 

			$this->_addContent($this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit'))
				->_addLeft($this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('promotionalpopup')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			   if(isset($data['stores'])) {
                    $data['stores_id'] = implode(",", $data['stores']);
                   unset($data['stores']);
                  }
				  
				 if(isset($data['customergroups'])) {
                    $data['customergroup_ids'] = implode(",", $data['customergroups']);
                   unset($data['customergroups']);
                  } 
				  
				  if(isset($data['cookie_value'])) {   
					$nospaces = str_replace('', '-', $data['cookie_value']); 
   					$data['cookie_value'] = preg_replace('/[^A-Za-z0-9\-]/', '', $nospaces); 
				  } 
				  
				  $data = $this->_filterDates($data, array('begin_time', 'end_time'));
				  
			 	   
			// main pop-up background
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = 'promotional-popups/assets/' . $_FILES['filename']['name'];
			}  else {       
   
       
   
          if(isset($data['filename']['delete']) && $data['filename']['delete'] == 1)
   
              $data['filename'] = '';
   
          else
   
              unset($data['filename']);
   
      }
	  
	  // md formfactor pop-up background
			if(isset($_FILES['filename_md']['name']) && $_FILES['filename_md']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename_md');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['filename_md']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename_md'] = 'promotional-popups/assets/' . $_FILES['filename_md']['name'];
			}  else {       
   
       
   
          if(isset($data['filename_md']['delete']) && $data['filename_md']['delete'] == 1)
   
              $data['filename_md'] = '';
   
          else
   
              unset($data['filename_md']);
   
      }
	  
	  // sm formfactor pop-up background
			if(isset($_FILES['filename_sm']['name']) && $_FILES['filename_sm']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename_sm');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['filename_sm']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename_sm'] = 'promotional-popups/assets/' . $_FILES['filename_sm']['name'];
			}  else {       
   
       
   
          if(isset($data['filename_sm']['delete']) && $data['filename_sm']['delete'] == 1)
   
              $data['filename_sm'] = '';
   
          else
   
              unset($data['filename_sm']);
   
      }
	  
	  // Close button background
			if(isset($_FILES['filename_close_btn']['name']) && $_FILES['filename_close_btn']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename_close_btn');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['filename_close_btn']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename_close_btn'] = 'promotional-popups/assets/' . $_FILES['filename_close_btn']['name'];
			}  else {       
   
       
   
          if(isset($data['filename_close_btn']['delete']) && $data['filename_close_btn']['delete'] == 1)
   
              $data['filename_close_btn'] = '';
   
          else
   
              unset($data['filename_close_btn']);
   
      }
	  
	  // modal_background upload
	  
	  if(isset($_FILES['modal_background']['name']) && $_FILES['modal_background']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('modal_background');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['modal_background']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['modal_background'] = 'promotional-popups/assets/' . $_FILES['modal_background']['name'];
			}  else {       
   
       
   
          if(isset($data['modal_background']['delete']) && $data['modal_background']['delete'] == 1)
   
              $data['modal_background'] = '';
   
          else
   
              unset($data['modal_background']);
   
      }
	  
	  ///
	  
	  // upload modal MP4 video background
	  if(isset($_FILES['modal_video_mp4']['name']) && $_FILES['modal_video_mp4']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('modal_video_mp4');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('mp4','m4v'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['modal_video_mp4']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['modal_video_mp4'] = 'promotional-popups/assets/' . $_FILES['modal_video_mp4']['name'];
			}  else {       
   
       
   
          if(isset($data['modal_video_mp4']['delete']) && $data['modal_video_mp4']['delete'] == 1)
   
              $data['modal_video_mp4'] = '';
   
          else
   
              unset($data['modal_video_mp4']);
   
      }
	  
	  // upload modal OGV video background
	  if(isset($_FILES['modal_video_ogv']['name']) && $_FILES['modal_video_ogv']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('modal_video_ogv');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('ogv'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'promotional-popups/assets' . DS;
					$uploader->save($path, $_FILES['modal_video_ogv']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['modal_video_ogv'] = 'promotional-popups/assets/' . $_FILES['modal_video_ogv']['name'];
			}  else {       
   
       
   
          if(isset($data['modal_video_ogv']['delete']) && $data['modal_video_ogv']['delete'] == 1)
   
              $data['modal_video_ogv'] = '';
   
          else
   
              unset($data['modal_video_ogv']);
   
      }
	  
	  			// end of modal uploads
	  			
			$model = Mage::getModel('promotionalpopup/promotionalpopup');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}
					
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('promotionalpopup')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('promotionalpopup')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('promotionalpopup/promotionalpopup');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $popupIds = $this->getRequest()->getParam('promotionalpopup');
        if(!is_array($popupIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($popupIds as $popupId) {
                    $popup = Mage::getModel('promotionalpopup/promotionalpopup')->load($popupId);
                    $popup->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($popupIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $popupIds = $this->getRequest()->getParam('promotionalpopup');
        if(!is_array($popupIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($popupIds as $popupId) {
                    $popup = Mage::getSingleton('promotionalpopup/promotionalpopup')
                        ->load($popupId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($popupIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'promotionalpopup.csv';
        $content    = $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'promotionalpopup.xml';
        $content    = $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	
	protected function _isAllowed()
	{
		return true;
	}
	
}
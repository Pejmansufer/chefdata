<?php
/**
 * WeaveApps_ResponsiveSlideshow Extension
 *
 * @category   WeaveApps
 * @package    WeaveApps_ResponsiveSlideshow
 * @copyright  Copyright (c) 2014 Weave Apps. (http://www.weaveapps.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class WeaveApps_ResponsiveSlideshow_Adminhtml_responsiveslideshowgroupController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('responsiveslideshow/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('responsiveslideshow/responsiveslideshowgroup')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
        }
        Mage::register('responsiveslideshowgroup_data', $model);
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function responsiveslideshowgridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit_tab_responsiveslideshow')->toHtml()
        );
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('responsiveslideshow/responsiveslideshowgroup')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('responsiveslideshowgroup_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('responsiveslideshow/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit'))
                    ->_addLeft($this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsiveslideshow')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {

        if ($data = $this->getRequest()->getPost()) {
            $responsiveslideshows = array();
            $availresponsiveslideshowIds = Mage::getModel('responsiveslideshow/responsiveslideshow')->getAllAvailBannerIds();
            
            parse_str($data['responsiveslideshowgroup_responsiveslideshows'], $responsiveslideshows);
            foreach ($responsiveslideshows as $k => $v) {
                if (preg_match('/[^0-9]+/', $k) || preg_match('/[^0-9]+/', $v)) {
                    unset($responsiveslideshows[$k]);
                }
            }
          $responsiveslideshowIds = array_intersect($availresponsiveslideshowIds, $responsiveslideshows);

         $data['banner_ids'] = implode(',', $responsiveslideshowIds);

            $model = Mage::getModel('responsiveslideshow/responsiveslideshowgroup');
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

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('responsiveslideshow')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsiveslideshow')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('responsiveslideshow/responsiveslideshowgroup')->load($this->getRequest()->getParam('id'));
                $filePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'responsiveslideshows' . DS . 'resize' . DS . $model->getGroupCode();              
                $model->delete();
                $this->removeFile($filePath);

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
        $responsiveslideshowIds = $this->getRequest()->getParam('responsiveslideshow');
        if (!is_array($responsiveslideshowIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($responsiveslideshowIds as $responsiveslideshowId) {
                    $responsiveslideshow = Mage::getModel('responsiveslideshow/responsiveslideshowgroup')->load($responsiveslideshowId);
                    $filePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'responsiveslideshows' . DS . 'resize' . DS . $responsiveslideshow->getGroupCode();
                    $responsiveslideshow->delete();
                    $this->removeFile($filePath);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($responsiveslideshowIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $responsiveslideshowIds = $this->getRequest()->getParam('responsiveslideshow');
        if (!is_array($responsiveslideshowIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($responsiveslideshowIds as $responsiveslideshowId) {
                    $responsiveslideshow = Mage::getSingleton('responsiveslideshow/responsiveslideshowgroup')
                                    ->load($responsiveslideshowId)
                                    ->setStatus($this->getRequest()->getParam('status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($responsiveslideshowIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'responsiveslideshow.csv';
        $content = $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshow_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'responsiveslideshow.xml';
        $content = $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshow_grid')
                        ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    protected function removeFile($file) {
        try {
            $io = new Varien_Io_File();
            $result = $io->rmdir($file, true);
        } catch (Exception $e) {

        }
    }

}
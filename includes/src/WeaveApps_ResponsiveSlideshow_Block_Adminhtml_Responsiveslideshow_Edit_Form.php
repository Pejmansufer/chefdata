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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshow_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   )
      );
      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}
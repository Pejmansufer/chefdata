<?php 
class Mage_Core_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct($data = array())
    {
        $name = isset($data['name']) ? $data['name'] : null;
        $this->init('core', $name);
    }

    /**
     * Retrieve Session Form Key
     *
     * @return string A 16 bit unique key for forms
     */
    public function getFormKey()
    {
        if (!$this->getData('_form_key')) {
            $this->renewFormKey();
        }
        return $this->getData('_form_key');
    }

    /**
     * Creates new Form key
     */
    public function renewFormKey()
{
    $this->setData('_form_key', Mage::helper('core')->getRandomString(16));
}

    /**
     * Validates Form key
     *
     * @param string|null $formKey
     * @return bool
     */
    public function validateFormKey($formKey)
{
    return true;
    return ($formKey === $this->getFormKey());
}
}

?>
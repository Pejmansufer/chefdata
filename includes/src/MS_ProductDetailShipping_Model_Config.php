<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */
class MS_ProductDetailShipping_Model_Config
{
    /**
     * A configuration path for the module active state setting
     *
     * @var string
     */
    const XML_PATH_ENABLED = 'ms_productdetailshipping/settings/enabled';


    /**
     * A configuration path for the country field usage
     *
     * @var string
     */
    const XML_PATH_USE_COUNTRY = 'ms_productdetailshipping/settings/use_country';

    /**
     * A configuration path for the region field usage
     *
     * @var string
     */
    const XML_PATH_USE_REGION = 'ms_productdetailshipping/settings/use_region';

    /**
     * A configuration path for the city field usage
     *
     * @var string
     */
    const XML_PATH_USE_CITY = 'ms_productdetailshipping/settings/use_city';

    /**
     * A configuration path for the postcode field usage
     *
     * @var string
     */
    const XML_PATH_USE_POSTCODE = 'ms_productdetailshipping/settings/use_postcode';

    /**
     * A configuration path for the coupon code field usage
     *
     * @var string
     */
    const XML_PATH_USE_COUPON_CODE = 'ms_productdetailshipping/settings/use_coupon_code';

    /**
     * A configuration path for the include shopping cart checkbox visibility
     *
     * @var string
     */
    const XML_PATH_USE_CART = 'ms_productdetailshipping/settings/use_cart';

    /**
     * A configuration path for the using of shopping cart items by default in calculation
     *
     * @var string
     */
    const XML_PATH_USE_CART_DEFAULT = 'ms_productdetailshipping/settings/use_cart_default';

	/**
     * A configuration path for the using of shopping cart items by default in calculation
     *
     * @var string
     */
    const XML_PATH_CAL_SHIPP = 'ms_productdetailshipping/settings/cal_shipp';
	
    const XML_PATH_RATE_SHIPP = 'ms_productdetailshipping/settings/rate_shipp';
		
    const XML_PATH_SHIPPMETHOD_SHIPP = 'ms_productdetailshipping/settings/shippmethod_shipp';		
	
    const XML_PATH_SERVICE_SHIPP = 'ms_productdetailshipping/settings/service_shipp';

    const XML_PATH_QUOTE_SHIPP = 'ms_productdetailshipping/settings/quote_shipp';
	
    const XML_PATH_LOADTEXT_SHIPP = 'ms_productdetailshipping/settings/loadtext_shipp';

	const XML_PATH_LOADIMAGE_SHIPP = 'ms_productdetailshipping/settings/loadimage_shipp';	

	const XML_PATH_SPECIFIERMSG = 'ms_productdetailshipping/settings/specificerrmsg';	
	
    /**
     * A configuration path for the default store country usage
     *
     * @var string
     */
    const XML_PATH_DEFAULT_COUNTRY = 'shipping/origin/country_id';


    /**
     * A configuration path for the list of layout handles for displaying of estimate form
     *
     * @var string
     */
    const XML_PATH_CONTROLLER_ACTIONS = 'ms/productshippingpage/controller_actions';

    /**
     * A configuration path for the display position on the page
     *
     * @var unknown_type
     */
    const XML_PATH_DISPLAY_POSITION = 'ms_productdetailshipping/settings/display_position';

    /**
     * Display positions for shipping estimation form
     * @var string
     */
    const DISPLAY_POSITION_RIGHT = 'right';
    const DISPLAY_POSITION_LEFT = 'left';
    const DISPLAY_POSITION_CUSTOM = 'custom';

    /**
     * Layout handles names for applying of positions
     *
     * @var string
     */
    const LAYOUT_HANDLE_LEFT = 'ms_productdetailshipping_left';
    const LAYOUT_HANDLE_RIGHT = 'ms_productdetailshipping_right';
	const LAYOUT_HANDLE_CUSTOM = 'ms_productdetailshipping_custom';

    /**
     * Retrive a configuration flag for the country field usage in the estimate
     *
     * @return boolean
     */
    public function useCountry()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_COUNTRY);
    }

    /**
     * Retrive a configuration flag for the region field usage in the estimate
     *
     * @return boolean
     */
    public function useRegion()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_REGION);
    }

    /**
     * Retrive a configuration flag for the city field usage in the estimate
     *
     * @return boolean
     */
    public function useCity()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CITY);
    }

    /**
     * Retrive a configuration flag for the postal code field usage in the estimate
     *
     * @return boolean
     */
    public function usePostcode()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_POSTCODE);
    }

    /**
     * Retrive a configuration flag for the coupon code field usage in the estimate
     *
     * @return boolean
     */
    public function useCouponCode()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_COUPON_CODE);
    }

    /**
     * Retrive a configuration flag
     * for visibility of the include cart items field
     *
     * @return boolean
     */
    public function useCart()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CART);
    }

    /**
     * Retrive a configuration flag
     * for using of the cart items in calculation
     *
     * @return boolean
     */
    public function useCartDefault()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CART_DEFAULT);
    }


    /**
     * Retrive default country
     *
     * @return string
     */
    public function getDefaultCountry()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_COUNTRY);
    }

    /**
     * Check the module active state configuration
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }

    /**
     * Retieve display type configuration value
     *
     * @return string
     */
    public function getDisplayPosition()
    {
        return Mage::getStoreConfig(self::XML_PATH_DISPLAY_POSITION);
    }

    /**
     * Retrieve layout handles list for applying of the form
     *
     * @return array
     */
    public function getControllerActions()
    {
        $actions = array();
        foreach (Mage::getConfig()->getNode(self::XML_PATH_CONTROLLER_ACTIONS)->children() as $action => $node) {
            $actions[] = $action;
        }

        return $actions;
    }
	
	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */
	 
	public function CalShipp(){
			return Mage::getStoreConfig(self::XML_PATH_CAL_SHIPP);
	}
	

	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function RateShipp(){
			return Mage::getStoreConfig(self::XML_PATH_RATE_SHIPP);
	}
	
	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */
	
	public function ShippmethodShipp(){
			return Mage::getStoreConfig(self::XML_PATH_SHIPPMETHOD_SHIPP);
	}

	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function ServiceShipp(){
			return Mage::getStoreConfig(self::XML_PATH_SERVICE_SHIPP);
	}

	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function QuoteShipp(){
			return Mage::getStoreConfig(self::XML_PATH_QUOTE_SHIPP);
	}

	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function LoadtextShipp(){
			return Mage::getStoreConfig(self::XML_PATH_LOADTEXT_SHIPP);
	}

	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function LoadimageShipp(){
			return Mage::getStoreConfig(self::XML_PATH_LOADIMAGE_SHIPP);
	}
	
	/**
     * Retrieve Text from admin for applying of the form
     *
     * @return string
     */

	public function Specifiermsg(){
			return Mage::getStoreConfig(self::XML_PATH_SPECIFIERMSG);
	}
}

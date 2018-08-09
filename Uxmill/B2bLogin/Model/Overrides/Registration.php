<?php
/**
 * @package Uxmill_B2bLogin
 * @copyright uxmill.co ${date}
 * @author durga
 */
namespace Uxmill\B2bLogin\Model\Overrides;

class Registration extends \Magento\Customer\Model\Registration
{

    private $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowed()
    {
        $forced_login_status = $this->scopeConfig->getValue('b2blogin/parameters/status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $forced_login_access = $this->scopeConfig->getValue('b2blogin/parameters/access_to_website', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if ($forced_login_access == '0' && $forced_login_status == '1') {
            return false;
        }
        
        return true;
    } // end isAllowed()
}//end class

<?php
/**
 * @package Uxmill_B2bLogin
 * @copyright uxmill.co ${date}
 * @author durga
 */
namespace Uxmill\B2bLogin\Observer;

use Magento\Framework\Event\ObserverInterface;

class ForceLoginObserver implements ObserverInterface
{

    private $scopeConfig;

    private $customerSession;

    private $customerUrl;

    private $context;

    private $contextHttp;

    /**
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig            
     * @param \Magento\Framework\App\Action\Context $context            
     * @param \Magento\Customer\Model\Session $customerSession            
     * @param \Magento\Framework\App\Http\Context $contextHttp            
     * @param \Magento\Customer\Model\Url $customerUrl            
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\App\Http\Context $contextHttp, \Magento\Customer\Model\Url $customerUrl)
    {
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->customerSession = $customerSession;
        $this->customerUrl = $customerUrl;
        $this->contextHttp = $contextHttp;
    }
 // end __construct()
    
    /**
     *
     * {@inheritDoc}
     *
     * @see \Magento\Framework\Event\ObserverInterface::execute()
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event_name = $observer->getEventName();
        
        $forced_login_status = $this->scopeConfig->getValue('b2blogin/parameters/status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $forced_login_access = $this->scopeConfig->getValue('b2blogin/parameters/access_to_website', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if ($forced_login_status) {
            $module_name = $this->context->getRequest()->getModuleName();
            $controller_name = $this->context->getRequest()->getControllerName();
            $action_name = $this->context->getRequest()->getActionName();
            
            $routeName = $module_name . "_" . $controller_name . "_" . $action_name;
            $isLoggedIn = $this->contextHttp->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
            
            if ($isLoggedIn || $module_name === 'api') {
                return $this;
            }
            
            if ($forced_login_access === '1' && ($controller_name === 'account' || $this->isAllowedRoute($routeName))) {
                return $this;
            }
            // in_array($routeName, $this->getAllowedRoute())
            
            if ($forced_login_access === '0' && ($this->isAllowedRoute($routeName))) {
                return $this;
            }
            
            $customer_login_url = $this->customerUrl->getLoginUrl();
            $this->context->getResponse()->setRedirect($customer_login_url);
        } // end if
        
        return $this;
    }
 // end execute()
    
    /**
     *
     * @param unknown $currentRouteName            
     * @return boolean
     */
    private function isAllowedRoute($currentRouteName)
    {
        $allowedRoutes = array(
            'brand',
            'contact',
            'cms',
            "customer_acccount"
        );
        
        foreach ($allowedRoutes as $allowedRoute) {
            if (preg_match("/{$allowedRoute}/i", $currentRouteName, $match)) {
                return true;
            }
        }
    } // end getAllowedRoute()
}//end class

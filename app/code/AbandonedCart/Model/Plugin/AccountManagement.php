<?php

namespace Ktpl\AbandonedCart\Model\Plugin;

class AccountManagement
{
    
    protected $_objectManager;    
    protected $_logger;

    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_objectManager = $objectManager;
        $this->_logger = $logger;
    }
    public function aroundIsEmailAvailable(\Magento\Customer\Model\AccountManagement $accountManagement,\Closure $proceed,$customerEmail,$websiteId=null)
    {
        $ret = $proceed($customerEmail,$websiteId);
        $session = $this->_getSession();
        if($session)
        {
            $quoteId = $session->getQuoteId();
            if($quoteId) {
                $quote = $this->_objectManager->get('\Magento\Quote\Model\Quote')->load($quoteId);
                $quote->setCustomerEmail($customerEmail);
                $quote->setUpdatedAt(date('Y-m-d H:i:s'));
                $quote->save();
            }
        }
        return $ret;
    }
    protected function _getSession()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }
}
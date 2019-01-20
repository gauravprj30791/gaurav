<?php 
namespace Test\Discount\Plugin;
 
class Product
{
	protected $_customerRepositoryInterface;
	protected $_customerSession;

	public function __construct(
    	\Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Customer\Model\Session $customerSession
	) {
	    $this->_customerSession = $customerSession;
	    $this->_customerRepositoryInterface = $customerRepositoryInterface;
	}
    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
    	if($this->_customerSession->isLoggedIn()){
    		$cust_id = $this->_customerSession->getId();
    		$customeratt = $this->_customerRepositoryInterface->getById($cust_id);
            $customerAttributeData = $customeratt->__toArray();
            $attrValue = $customerAttributeData['custom_attributes']['customer_discount']['value'];
    		$result = $result - ($result*$attrValue/100);
  			return $result;
		}
        else{        
            return $result;
        }        
    }
}

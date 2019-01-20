<?php
namespace Test\Discount\Plugin\Customer\Controller;

class Save
{
    protected $customer;

protected $customerFactory;

public function __construct(
    \Magento\Customer\Model\Customer $customer,
    \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory
)
{
    $this->customer = $customer;
    $this->customerFactory = $customerFactory;
}

    public function afterExecute(
        \Magento\Customer\Controller\Adminhtml\Index\Save $subject,
        $result
    ) {
        $d_customer = $subject->getRequest()->getParams();      
        $customerId = "1";
        $customer = $this->customer->load($customerId);        
        $customerData = $customer->getDataModel();
        $customerData->setCustomAttribute('customer_discount',$d_customer['customer']['customer_discount']);
        $customer->updateData($customerData);
        $customerResource = $this->customerFactory->create();
        $customerResource->saveAttribute($customer, 'customer_discount');
        return $result;
    }
}
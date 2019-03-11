<?php

namespace Digital\Careerprocess\Controller\Adminhtml\Careerprocess;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{    
    protected $resultPagee;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {        
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Digital_Careerprocess::Careerprocess');
        $resultPage->addBreadcrumb(__('Digital'), __('Digital'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Manage Career Process'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Career Process'));

        return $resultPage;
    }
}
?>
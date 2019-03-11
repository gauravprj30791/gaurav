<?php
namespace Digital\Productlabel\Controller\Adminhtml\Image;

use Digital\Productlabel\Controller\Adminhtml\Image;

class Index extends Image
{    
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Digital_Productlabel::image');
        $resultPage->getConfig()->getTitle()->prepend(__('Productlabel Details'));
        $resultPage->addBreadcrumb(__('Productlabel Details'), __('Productlabel Details'));
        return $resultPage;
    }
}

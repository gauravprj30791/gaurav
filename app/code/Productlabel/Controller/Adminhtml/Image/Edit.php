<?php
namespace Digital\Productlabel\Controller\Adminhtml\Image;

use Digital\Productlabel\Controller\Adminhtml\Image;

class Edit extends Image
{    
    public function execute()
    {
        $imageId = $this->getRequest()->getParam('label_id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Digital_Productlabel::image')
            ->addBreadcrumb(__('Images'), __('Images'))
            ->addBreadcrumb(__('Manage Data'), __('Manage Data'));

        if ($imageId === null) {
            $resultPage->addBreadcrumb(__('New Data'), __('New Data'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Data'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Data'), __('Edit Data'));
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Data'));
        }
        return $resultPage;
    }
}

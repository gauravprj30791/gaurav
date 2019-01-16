<?php
namespace Gaurav\Student\Controller\Adminhtml\Image;

use Gaurav\Student\Controller\Adminhtml\Image;

class Index extends Image
{    
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Gaurav_Student::image');
        $resultPage->getConfig()->getTitle()->prepend(__('Student Details'));
        $resultPage->addBreadcrumb(__('Student Details'), __('Student Details'));
        return $resultPage;
    }
}

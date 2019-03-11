<?php

namespace Digital\Careerprocess\Controller\Adminhtml\Careerprocess;

class Grid extends \Magento\Customer\Controller\Adminhtml\Careerprocess
{
    /**
     * Customer grid action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}

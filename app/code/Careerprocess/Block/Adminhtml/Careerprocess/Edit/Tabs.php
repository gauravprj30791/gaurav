<?php
namespace Digital\Careerprocess\Block\Adminhtml\Careerprocess\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {        
        parent::_construct();
        $this->setId('careerprocess_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Careerprocess Information'));
    }
}
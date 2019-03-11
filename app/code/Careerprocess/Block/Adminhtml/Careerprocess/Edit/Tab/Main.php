<?php

namespace Digital\Careerprocess\Block\Adminhtml\Careerprocess\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{    
    protected $_systemStore;
    protected $_status;
    protected $wysiwyg;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Digital\Careerprocess\Model\Status $status,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwyg,
        array $data = []
    ) {        
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        $this->wysiwyg = $wysiwyg;     
        parent::__construct($context, $registry, $formFactory, $data);
    }
    protected function _prepareForm()
    {        
        $model = $this->_coreRegistry->registry('careerprocess');
        $isElementDisabled = false;
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Career Process Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'short_description',
            'textarea',
            [
                'name' => 'short_description',
                'label' => __('Short Description'),
                'title' => __('Short Description'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => true,
                'config'    => $this->wysiwyg->getConfig(),
                'wysiwyg'   => true
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                
                'disabled' => $isElementDisabled
            ]
        ); 
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
				'required' => true,
                'options' =>  $this->_status->getOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
           'store_view',
           'multiselect',
           array(
             'name'     => 'store_view[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true)
           )
        );  
            if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }        

        $form->setValues($model->getData());
        $this->setForm($form);
		
        return parent::_prepareForm();
    }
    public function getTabLabel()
    {
        return __('Career Process Information');
    }
    public function getTabTitle()
    {
        return __('Career Process Information');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}

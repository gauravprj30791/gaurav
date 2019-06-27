<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2018 Atwix (https://www.atwix.com/)
 * @package Atwix_DynamicFields
 */

namespace Ktpl\AbandonedCart\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\Factory as elementFactory;

/**
 * Class AdditionalEmail
 */
class AdditionalEmail extends AbstractFieldArray
{
    /**
     * {@inheritdoc}
     */
    protected $units;
    public $elementFactory;
    public function __construct(
            Context $context,
            elementFactory $elementFactory,
            array $data = [])
        {
            $this->elementFactory   = $elementFactory;
            $this->_addAfter        = false;
            parent::__construct($context, $data);
        }        
    protected function _prepareToRender()
    {           
        $this->addColumn('email_subject', ['label' => __('Email Subject'),'size' => '200px']);
        $this->addColumn('sendmail_after',['label' => __('Send Email After'),'size' => '200px']);
        $this->addColumn('unit',['label' => __('Unit'),'size' => '200px']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Email');
    }
    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'unit' && isset($this->_columns[$columnName])) {
            $options = $this->getElement()->getValues();
            $element = $this->elementFactory->create('select');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setValues(
                $options
            );

            return str_replace("\n", '', $element->getElementHtml());
        }

        return parent::renderCellTemplate($columnName);
    }

    public function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        $options['option_' . $this->getAttributeRenderer()->calcOptionHash(
            $row->getData('unit')
        )] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
    private function getAttributeRenderer()
    {
        $this->attributeRenderer = $this->getLayout()->createBlock(
            Select::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );

        return $this->attributeRenderer;
    } 
}
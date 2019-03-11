<?php
 
namespace Digital\Productlabel\Model\AttributeSet;
 
class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
   	protected $eavConfig;
	public function __construct(    
	    \Magento\Eav\Model\Config $eavConfig
	){
    $this->eavConfig = $eavConfig;
}
    public function getAllOptions()
    {
    	$attribute = $this->eavConfig->getAttribute('catalog_product', 'product_labels');
		$options = $attribute->getSource()->getAllOptions();		
    	return $options;
 
    }
 
}
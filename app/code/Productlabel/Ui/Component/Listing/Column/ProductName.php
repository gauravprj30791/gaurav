<?php
namespace Digital\Productlabel\Ui\Component\Listing\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ProductName extends Column
{
    protected $eavConfig;
    protected $storeManager;    
    protected $urlBuilder;
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Magento\Eav\Model\Config $eavConfig,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);        
        $this->urlBuilder = $urlBuilder;
        $this->eavConfig = $eavConfig;
    }

    public function prepareDataSource(array $dataSource)
    {
        foreach ($dataSource['data']['items'] as &$item) {
            $attribute = $this->eavConfig->getAttribute('catalog_product', 'product_labels');
            $options = $attribute->getSource()->getAllOptions();                
            foreach($options as $opt_val){                    
                if($opt_val['value'] == $item['productlabel_name']){
                    $item['productlabel_name'] = $opt_val['label'];
                }
            }  
        }            
    return $dataSource;
    }
}
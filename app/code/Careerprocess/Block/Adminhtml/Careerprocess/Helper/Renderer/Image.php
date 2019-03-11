<?php 
namespace Digital\Careerprocess\Block\Adminhtml\Careerprocess\Helper\Renderer;
 
class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{ 
    protected $_storeManager;

     
    protected $_bannerFactory;
 
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Digital\Careerprocess\Model\Careerprocess $bannerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_bannerFactory = $bannerFactory;
    }
 
    public function render(\Magento\Framework\DataObject $row)
    {
        
        $srcImage = '';
        $storeViewId = $this->getRequest()->getParam('store');
        $banner = $this->_bannerFactory->load($row->getId());
        $srcImage = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $banner->getImage(); 
        if(@GetImageSize($srcImage)){
            return '<image width="150" height="50" src ="'.$srcImage.'" alt="'.$banner->getImage().'" >';
        }  
        return '<span>No Image Found.</span>';
        
    }
}

<?php
namespace Gaurav\Imageresize\Block\Product;
// use Magento\Framework\View\Element\Template;
// use Magento\Catalog\Api\CategoryRepositoryInterface;
// use Magento\Catalog\Block\Product\ProductList\Toolbar;
// use Magento\Catalog\Model\Category;
// use Magento\Catalog\Model\Config;
// use Magento\Catalog\Model\Layer;
// use Magento\Catalog\Model\Layer\Resolver;
// use Magento\Catalog\Model\Product;
// use Magento\Catalog\Model\ResourceModel\Product\Collection;
// use Magento\Catalog\Pricing\Price\FinalPrice;
// use Magento\Eav\Model\Entity\Collection\AbstractCollection;
// use Magento\Framework\App\ActionInterface;
// use Magento\Framework\App\Config\Element;
// use Magento\Framework\Data\Helper\PostHelper;
// use Magento\Framework\DataObject\IdentityInterface;
// use Magento\Framework\Exception\NoSuchEntityException;
// use Magento\Framework\Pricing\Render;
// use Magento\Framework\Url\Helper\Data;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct{
	protected $_registry;
  protected $_filesystem ;
  protected $_imageFactory;

	public function __construct(
            \Magento\Catalog\Block\Product\Context $context,        
            \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
            \Magento\Catalog\Model\Layer\Resolver $layerResolver,
            \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
            \Magento\Framework\Url\Helper\Data $urlHelper,
            \Magento\Framework\Registry $registry,
            \Magento\Framework\Filesystem $filesystem,         
            \Magento\Framework\Image\AdapterFactory $imageFactory, 
            // \Vendor\Mymodule\Helper\Data $dataHelper,
            array $data = []
        ) {
            $this->_registry = $registry;
            $this->_filesystem = $filesystem;               
            $this->_imageFactory = $imageFactory;        
            parent::__construct(
                $context,
                $postDataHelper,
                $layerResolver,
                $categoryRepository,
                $urlHelper,
                $data
            );
        }

       public function test(){
            $this->dataHelper->getHelperFunction();
       }

       public function resize($image, $width = 400, $height=400)
	    {
	        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/category/').$image;
	        if (!file_exists($absolutePath)) return false;
	        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/'.$width.'/').$image;
	        if (!file_exists($imageResized)) { // Only resize image if not already exists.
	            //create image factory...
	            $imageResize = $this->_imageFactory->create();         
	            $imageResize->open($absolutePath);
	            $imageResize->constrainOnly(TRUE);         
	            $imageResize->keepTransparency(TRUE);         
	            $imageResize->keepFrame(FALSE);         
	            $imageResize->keepAspectRatio(TRUE);         
	            $imageResize->resize($width,$height);  
	            //destination folder                
	            $destination = $imageResized ;    
	            //save image      
	            $imageResize->save($destination);
	        } 
	        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$width.'/'.$image;
          
	        return $resizedURL;
  	}
  	public function getCurrentCategoryData(){
  		$category = $this->_registry->registry('current_category');//get current category
      return $category->getData('thumbnail');
  	}
}
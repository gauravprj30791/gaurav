<?php
namespace Digital\Categorytree\Block;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\View\Element\Template;

class Sidebar extends Template
{

    const SCOPE_ROOT_CATEGORY = 'test/categorytree/rootcategories';
    const SCOPE_PARENT_CATEGORY = 'test/categorytree/parentcategories';

    protected $_coreRegistry;
    private $helper;
    protected $categories;
    protected $scopeConfig;
    protected $_categoryHelper;
    protected $categoryFlatConfig;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,  
        \Magento\Framework\Registry $registry,        
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $_storeManager,
        \Magento\Catalog\Model\CategoryFactory $categories,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        $data = [ ]
    )
    {        
        
        $this->_coreRegistry = $registry;        
        $this->_storeManager = $_storeManager;
        $this->categories = $categories;
        $this->scopeConfig = $scopeConfig;
        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig        = $categoryFlatState;
        parent::__construct($context, $data);
    }
    public function EnableRootCategory() {
         $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
         return $this->scopeConfig->getValue(self::SCOPE_ROOT_CATEGORY, $storeScope);
     }
    public function EnableParentCategory() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::SCOPE_PARENT_CATEGORY, $storeScope);
    }
    public function getCategoriesAccord(){
       $category = $this->_coreRegistry->registry('current_category');
       $childCatId = $category->getId();
       $childCategory = $this->categories->create()->load($childCatId);
       $child_category = $childCategory->getChildren();
       $cat_id = explode(',', $child_category);
       return $cat_id;
    }
    public function getCategoryName($cat_id)
    {       
        return $this->categories->create()->load($cat_id);
        
    }
    public function getCurrentCategory(){
        $curr_category = $this->_coreRegistry->registry('current_category');
        return $curr_category->getId();
    }
    public function getAllCategories(){        
        $categories = $this->categories->create()->getCollection()
        ->addAttributeToSelect('*')
        ->setStore($this->_storeManager->getStore());
        return $categories;
    }
    public function getCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        $cacheKey = sprintf('%d-%d-%d-%d', $this->getSelectedRootCategory(), $sorted, $asCollection, $toLoad);
        if ( isset($this->_storeCategories[ $cacheKey ]) )
        {
            return $this->_storeCategories[ $cacheKey ];
        }
        /**
         * Check if parent node of the store still exists
         */
        $category = $this->categories->create();

        $storeCategories = $category->getCategories($this->getSelectedRootCategory(), $recursionLevel = 0, $sorted, $asCollection, $toLoad);
        $this->_storeCategories[ $cacheKey ] = $storeCategories;
        return $storeCategories;
    }
    public function getSelectedRootCategory()
    {
        return $this->_storeManager->getStore()->getRootCategoryId();
    }
    public function getChildCategoryView($category, $html = '', $level = 1)
    {   
        // Check if category has children
        if ( $category->hasChildren() )
        {  
            $childCategories = $this->getSubcategories($category);
            $childCount = (int)$category->getChildrenCount();
            if ( $childCount > 0 )
            {   
                $html .= '<ul class="o-list o-list--unstyled" style="display:none">';
                // Loop through children categories
                foreach ( $childCategories as $childCategory )
                {
                    $html .= '<li class="level' . $level . '">';
                    $html .= '<a href="' . $this->getCategoryUrl($childCategory) . '" title="' . $childCategory->getName() . '">' . $childCategory->getName() . '</a>';
                    if ($childCategory->hasChildren())
                    {
                        $html .= '<span class="expand"><i class="fa fa-plus"></i></span>';
                        $html .= $this->getChildCategoryView($childCategory, '', ($level + 1));
                    }

                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        return $html;
    }
    public function getSubcategories($category)
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        return $subcategories;
    }
    public function getCategoryUrl($category)
    {
        return $this->_categoryHelper->getCategoryUrl($category);
    }
    public function isActive($category)
    {
        $activeCategory = $this->_coreRegistry->registry('current_category');
        $activeProduct  = $this->_coreRegistry->registry('current_product');

        if ( !$activeCategory )
        {
            if ( $activeProduct !== null )
            {
                return in_array($category->getId(), $activeProduct->getCategoryIds());
            }

            return false;
        }
        if ( $this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource() AND
            $category->getId() == $activeCategory->getId()
        )
        {
            return true;
        }
        $childrenIds = $category->getAllChildren(true);
        if ( !is_null($childrenIds) AND in_array($activeCategory->getId(), $childrenIds) )
        {
            return true;
        }
        return (($category->getName() == $activeCategory->getName()) ? true : false);
    }
}
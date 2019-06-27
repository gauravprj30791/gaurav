<?php
namespace Ktpl\AbandonedCart\Controller\Abandoned;
class Loadquote extends \Magento\Framework\App\Action\Action
{
    protected $_objectManager;
    protected $_helper;
    protected $_resultPageFactory;
    protected $_logger;
    protected $cartHelper;
    protected $quoteFactory;
    protected $quoteModel;
    protected $_transportBuilder;
    protected $_storeManager;
    protected $_productRepositoryFactory;
    protected $_logo;
    protected $scopeConfig;
    protected $helperFactory;
    protected $_coreSession;

    const XML_PATH_STATUS_RECIPIENT = 'abandonedcart/general/enable';    
    const XML_PATH_EMAIL_RECIPIENT = 'abandonedcart/general/identity';
    const XML_PATH_ADD_FIELDS_RECIPIENT = 'abandonedcart/general/add_fields';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Ktpl\AbandonedCart\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\ResourceModel\Quote $quoteModel,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Helper\ImageFactory $helperFactory,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    )
    {
        parent::__construct($context);
        $this->_objectManager = $context->getObjectManager();
        $this->_helper = $helper;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_logger = $logger;
        $this->cartHelper = $cartHelper;
        $this->quoteFactory = $quoteFactory;
        $this->quoteModel = $quoteModel;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->_logo = $logo;
        $this->scopeConfig = $scopeConfig;
        $this->helperFactory = $helperFactory;
        $this->_coreSession = $coreSession;
    }
    public function execute()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $status = $this->scopeConfig->getValue(self::XML_PATH_STATUS_RECIPIENT, $storeScope);
        if($status == 1){

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $collection = $objectManager->get('Magento\Reports\Model\ResourceModel\Quote\Collection');
            $store = $this->_storeManager->getStore()->getId();
            $store_url = $this->_storeManager->getStore()->getBaseUrl();        
            $collection->prepareForAbandonedReport([$store]);
            $rows = $collection->load();            
            $add_fields = $this->scopeConfig->getValue(self::XML_PATH_ADD_FIELDS_RECIPIENT, $storeScope);
            $fields_value = unserialize($add_fields);
            $fields = array_values($fields_value);
            foreach($rows as $Item_Col){  

                if($Item_Col->getKtplAbandonedcartFlag())
                {
                    if(array_key_exists($Item_Col->getKtplAbandonedcartFlag(),$fields))
                    {
                        $emails = $fields[$Item_Col->getKtplAbandonedcartFlag()];
                    }
                    else
                    {
                        continue;
                    }
                }
                else
                {

                    $emails = $fields[0];   
                }

                $subject = $emails['email_subject'];
                $time = $emails['sendmail_after'];
                $unit = $emails['unit'];
                $s_time = $time.' '.$unit;
                $send_time = $time.' '.$unit;

                $str_time = strtotime($Item_Col->getUpdatedAt() . " +".$send_time);
                $current_time = strtotime("now");                
                echo '<br/>';
                if($str_time >= $current_time){                    
                     $cust_name = $Item_Col->getCustomerFirstname().' '.$Item_Col->getCustomerLastname();
                        $cust_email =  $Item_Col->getCustomerEmail();
                    if($Item_Col->getItemsCount() != 0){

                        if($Item_Col->getKtplAbandonedcartFlag() < count($fields)){
                            $Item_Col->setKtplAbandonedcartFlag($Item_Col->getKtplAbandonedcartFlag()+1);
                            $Item_Col->setKtplAbandonedcartToken(date('Y-m-d H:i:s'));
                            $Item_Col->save();
                            $quote = $this->quoteFactory->create()->loadByCustomer($Item_Col->getCustomerId());
                            $items =$quote->getAllVisibleItems();
                            $html = "";
                            $html .= "<table><tr><th>Image</th><th>Name</th></tr>";
                            foreach($items as $item){
                                 $product = $this->_productRepositoryFactory->create()->getById($item->getProductId());
                                 $pro_name = $item->getName();                          
                                 $imagehelper = $this->helperFactory->create();
                                 $pro_image = $imagehelper->init($product,'product_small_image')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();                            
                                 $html .= "<tr><td><img src='".$pro_image."' alt='Product' title='Product' width='200' height='87' /></td><td>".$pro_name."</td></tr>";
                            }   
                            $html .= "</table>";                                                   
                            $sender = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope);
                            $transport = $this->_transportBuilder->setTemplateIdentifier('abandonedcart_general_template1')
                                ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
                                ->setTemplateOptions(
                                        [
                                            'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                                        ]
                                )
                                ->setTemplateVars(
                                    [
                                        'store' => $this->_storeManager->getStore(),
                                        'subject' => $subject,
                                        'name' => $cust_name,
                                        'logo_alt' => 'Test Image',
                                        'html' => $html,
                                        'logo_url' => $this->_logo->getLogoSrc(),                               
                                    ]
                                )
                                ->setFrom($sender)                    
                                ->addTo($cust_email, $cust_name)
                                ->getTransport();
                            $transport->sendMessage();
                        }
                    }
                }                 
            } 
        }    
        return ;        
    }       
}
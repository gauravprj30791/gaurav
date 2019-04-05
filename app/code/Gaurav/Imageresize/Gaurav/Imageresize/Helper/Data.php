<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_EmailDemo
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Gaurav\Imageresize\Helper;
 
use Magento\Customer\Model\Session;
 
/**
 * Webkul EmailDemo Helper. you can write email sending code where ever you want.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_EMAIL_DEMO = 'emaildemo/email/email_demo_template';
 
    protected $_inlineTranslation;
 
    protected $_transportBuilder;
 
    protected $_template;
 
    protected $_storeManager;
 
    /**
     * @param Magento\Framework\App\Helper\Context              $context
     * @param Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder
     * @param Magento\Store\Model\StoreManagerInterface         $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Webkul\EmailDemo\Model\Mail\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) 
    {
    	echo 'hello'; die;
        $this->_objectManager = $objectManager;
        parent::__construct($context);
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
    }
 
    /**
     * [generateTemplate description].
     *
     * @param Mixed $emailTemplateVariables
     * @param Mixed $senderInfo
     * @param Mixed $receiverInfo
     */
    public function generateTemplate()
    {
        $pdfFile = 'pdf_file_path/email.pdf';
 
        $emailTemplateVariables['message'] = 'This is a test message.';
        //load your email tempate
        $this->_template  = $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_DEMO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getStoreId()
        );
        $this->_inlineTranslation->suspend();
 
        $this->_transportBuilder->setTemplateIdentifier($this->_template)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->_storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars($emailTemplateVariables)
                ->setFrom([
                    'name' => 'Gaurav Prajapati',
                    'email' => 'gauravprj@gmail.com',
                ])
                ->addTo('rockygavin32@gmail.com', 'Rocky Gavin')
                ->addAttachment(file_get_contents($pdfFile)); //Attachment goes here.
 
        try {
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            echo $e->getMessage(); die;
        }
    }

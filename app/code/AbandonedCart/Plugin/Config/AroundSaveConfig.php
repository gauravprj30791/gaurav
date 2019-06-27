<?php

namespace Ktpl\AbandonedCart\Plugin\Config;

class AroundSaveConfig
{
    private $scopeConfig;
    protected $configWriter;
    const XML_PATH_ADD_FIELD_RECIPIENT = 'abandonedcart/general/add_fields';
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;

    }
    public function afterSave(\Magento\Config\Model\Config $subject)
    {        
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $add_fields = $this->scopeConfig->getValue(self::XML_PATH_ADD_FIELD_RECIPIENT, $storeScope);
        $field_val = unserialize($add_fields);        
        $field_array = array_values($field_val);
        $tmp = Array();
        foreach($field_array as $ma){
            if($ma['unit'] == "Hours"){
                $tmp[] = $ma["sendmail_after"]*60;
            }
            else if($ma['unit'] == "Days"){
                $tmp[] = $ma["sendmail_after"]*24*60;
            }
            else{
             $tmp[] = $ma["sendmail_after"] ;  
            }
        }      
        array_multisort($tmp, $field_array);
        $this->configWriter
            ->save('abandonedcart/general/add_fields', serialize($field_array), 'default', $scopeId = 0);
        return $field_array;
    }
}
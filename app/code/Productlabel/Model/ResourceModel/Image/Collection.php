<?php
namespace Digital\Productlabel\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{    
    protected $_idFieldName = 'label_id';
    protected function _construct()
    {
        $this->_init(
            'Digital\Productlabel\Model\Image',
            'Digital\Productlabel\Model\ResourceModel\Image'
        );
    }
}

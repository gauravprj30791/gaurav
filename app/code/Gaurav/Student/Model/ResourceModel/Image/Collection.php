<?php
namespace Gaurav\Student\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{    
    protected $_idFieldName = 'student_id';
    protected function _construct()
    {
        $this->_init(
            'Gaurav\Student\Model\Image',
            'Gaurav\Student\Model\ResourceModel\Image'
        );
    }
}

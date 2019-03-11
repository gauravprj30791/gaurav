<?php

namespace Digital\Careerprocess\Model\ResourceModel\Careerprocess;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Digital\Careerprocess\Model\Careerprocess', 'Digital\Careerprocess\Model\ResourceModel\Careerprocess');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>
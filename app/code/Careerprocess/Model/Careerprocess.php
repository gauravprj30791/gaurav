<?php
namespace Digital\Careerprocess\Model;

class Careerprocess extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Digital\Careerprocess\Model\ResourceModel\Careerprocess');
    }
}
?>
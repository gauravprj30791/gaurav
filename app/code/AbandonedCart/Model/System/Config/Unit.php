<?php
/**
 * Ktpl_Abandonedcart Magento JS component
 *
 * @category    Ktpl
 * @package     Ktpl_Abandonedcart
 * @author      Ktpl Team <info@Ktpl.com>
 * @copyright   Ktpl (http://Ktpl.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Ktpl\AbandonedCart\Model\System\Config;

class Unit extends \Magento\Framework\View\Element\Html\Select
{
    /**
 * @var \Magento\Config\Model\Config\Source\Yesno
 */
protected $yesNo;

public function __construct(
    \Magento\Config\Model\Config\Source\Yesno $yesNo,
    \Magento\Backend\Block\Template\Context $context, array $data = [])
{
    parent::__construct($context, $data);
    $this->yesNo = $yesNo;
}


public function _toHtml()
{
    if (!$this->getOptions()) {
        foreach ($this->yesNo->toOptionArray() as $option) {
            $this->addOption($option['value'], $option['label']);
        }
    }

    return parent::_toHtml();
}

public function getName()
{
    return $this->getInputName();
}
}
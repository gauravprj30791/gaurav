<?php
namespace Ktpl\AbandonedCart\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Select implements OptionSourceInterface
{
    const VALUE_MINUTES       = 'Minutes';
    const VALUE_HOURS         = 'Hours';
    const VALUE_DAYS          = 'Days';   

    public function toOptionArray()
    {
        return [
            ['value' => self::VALUE_MINUTES,      'label'   => __('Minutes')],
            ['value' => self::VALUE_HOURS,       'label'   => __('Hours')],
            ['value' => self::VALUE_DAYS, 'label'   => __('Days')]
        ];
    }

    /**
     * Get options as key value pair
     *
     * @return array
     */
    public function toArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}

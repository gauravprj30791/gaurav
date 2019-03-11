<?php
namespace Digital\Productlabel\Block\Adminhtml\Image\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete extends Generic implements ButtonProviderInterface
{
    public function getButtonData()
    {
        $data = [];
        if ($this->getImageId()) {
            $data = [
                'label' => __('Delete Label'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['label_id' => $this->getImageId()]);
    }
}

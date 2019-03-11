<?php
namespace Digital\Careerprocess\Controller\Adminhtml\Careerprocess;

use Magento\Backend\App\Action;

class MassStatus extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $itemIds = $this->getRequest()->getParam('careerprocess');
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                $status = (int) $this->getRequest()->getParam('status');
                foreach ($itemIds as $postId) {
                    $post = $this->_objectManager->create('Digital\Careerprocess\Model\Careerprocess')->load($postId);
                        $post->setStatus($status)->save();                     
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been updated.', count($itemIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('careerprocess/*/index');
    }

}
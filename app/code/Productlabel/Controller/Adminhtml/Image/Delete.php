<?php
namespace Digital\Productlabel\Controller\Adminhtml\Image;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Digital\Productlabel\Controller\Adminhtml\Image;

class Delete extends Image
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $imageId = $this->getRequest()->getParam('label_id');
        if ($imageId) {
            try {
                $this->imageRepository->deleteById($imageId);
                $this->messageManager->addSuccessMessage(__('The image has been deleted.'));
                $resultRedirect->setPath('sampleimageuploader/image/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The image no longer exists.'));
                return $resultRedirect->setPath('sampleimageuploader/image/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('sampleimageuploader/image/index', ['label_id' => $imageId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the image'));
                return $resultRedirect->setPath('sampleimageuploader/image/edit', ['label_id' => $imageId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the image to delete.'));
        $resultRedirect->setPath('sampleimageuploader/image/index');
        return $resultRedirect;
    }
}

<?php
namespace Gaurav\Student\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Gaurav\Student\Api\ImageRepositoryInterface;
use Gaurav\Student\Api\Data\ImageInterface;
use Gaurav\Student\Api\Data\ImageInterfaceFactory;
use Gaurav\Student\Controller\Adminhtml\Image;
use Gaurav\Student\Model\Uploader;
use Gaurav\Student\Model\UploaderPool;

class Save extends Image
{
    protected $messageManager;
    protected $imageRepository;
    protected $imageFactory;
    protected $dataObjectHelper;
    protected $uploaderPool;
    public function __construct(
        Registry $registry,
        ImageRepositoryInterface $imageRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Manager $messageManager,
        ImageInterfaceFactory $imageFactory,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool,
        Context $context
    ) {
        parent::__construct($registry, $imageRepository, $resultPageFactory, $dateFilter, $context);
        $this->messageManager   = $messageManager;
        $this->imageFactory      = $imageFactory;
        $this->imageRepository   = $imageRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
       
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('student_id');            
            if ($id) {
                $model = $this->imageRepository->getById($id);
            } else {
                unset($data['student_id']);
                $model = $this->imageFactory->create();               
            }

            try {
                $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);
                $data['image'] = $image;
                $this->dataObjectHelper->populateWithArray($model, $data, ImageInterface::class);
                $this->imageRepository->save($model);

                if ($id) {
                    $model1 = $this->imageRepository->getById($id);
                } else {
                    $model1 = $this->imageRepository->getById($model->getId());
                }
                $model1->setStudentName($data['student_name']);
                $model1->setStudentNo($data['student_no']);
                $model1->save();
            
                $this->messageManager->addSuccessMessage(__('You saved this image.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['student_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the image:' . $e->getMessage())
                );
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['student_id' => $this->getRequest()->getParam('student_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }
}

<?php
namespace Digital\Productlabel\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Digital\Productlabel\Api\ImageRepositoryInterface;
use Digital\Productlabel\Api\Data\ImageInterface;
use Digital\Productlabel\Api\Data\ImageInterfaceFactory;
use Digital\Productlabel\Controller\Adminhtml\Image;
use Digital\Productlabel\Model\Uploader;
use Digital\Productlabel\Model\UploaderPool;

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
            $id = $this->getRequest()->getParam('label_id');            
            if ($id) {
                $model = $this->imageRepository->getById($id);
            } else {
                unset($data['label_id']);
                $model = $this->imageFactory->create();               
            }

            try {
                $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);                
                $data['image'] = $image;
                $this->dataObjectHelper->populateWithArray($model, $data, ImageInterface::class);               
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $labelCollection = $objectManager->create('Digital\Productlabel\Model\ResourceModel\Image\CollectionFactory');
                $collection = $labelCollection->create()->load();
                $labels = array();
                foreach($collection as $label_data){
                    $labels[] = $label_data['productlabel_name'];
                }
                if (!in_array($data['productlabel_name'], $labels))
                {
                    $this->imageRepository->save($model);
                    $model->setProductlabelName($data['productlabel_name']);
                    $model->save();
                    $this->messageManager->addSuccessMessage(__('You saved Product label.'));
                    $this->_getSession()->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['label_id' => $model->getId(), '_current' => true]);
                    }
                    return $resultRedirect->setPath('*/*/');
                }
                else{
                    if($id){
                        $this->imageRepository->save($model);
                        $this->messageManager->addSuccessMessage(__('Product Label Updated Successfully.'));   
                    }
                    else{
                         $this->messageManager->addErrorMessage(__('Product Label is Already Exists.'));                        
                    }
                    return $resultRedirect->setPath('*/*/');
                }        
               
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
            return $resultRedirect->setPath('*/*/edit', ['label_id' => $this->getRequest()->getParam('label_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }
}

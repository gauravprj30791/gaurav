<?php
namespace Gaurav\Student\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Gaurav\Student\Model\Uploader;

class Upload extends Action
{
    const ACTION_RESOURCE = 'Gaurav_Student::image';
    protected $uploader;
    public function __construct(
        Context $context,
        Uploader $uploader
    ) {
        parent::__construct($context);
        $this->uploader = $uploader;
    }
    public function execute()
    {
        try {
            $result = $this->uploader->saveFileToTmpDir($this->getFieldName());

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
    protected function getFieldName()
    {
        return $this->_request->getParam('field');
    }
}

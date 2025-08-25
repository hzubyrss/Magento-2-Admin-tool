<?php
namespace Storeteam\AdminTools\Controller\Adminhtml\System;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Storeteam\AdminTools\Helper\Data as HelperData;

class Execute extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param HelperData $helperData
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        HelperData $helperData
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helperData = $helperData;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Storeteam_AdminTools::system_commands');
    }

    /**
     * Execute command action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        
        if (!$this->helperData->isEnabled()) {
            return $result->setData([
                'success' => false,
                'message' => __('Admin Tools module is disabled.')
            ]);
        }
        
        try {
            $command = $this->getRequest()->getParam('command');
            
            switch ($command) {
                case 'setup:upgrade':
                    $output = $this->helperData->executeMagentoCommand('setup:upgrade');
                    $message = __('Setup upgrade completed successfully.');
                    break;
                    
                case 'setup:di:compile':
                    $output = $this->helperData->executeMagentoCommand('setup:di:compile');
                    $message = __('DI compilation completed successfully.');
                    break;
                    
                case 'setup:static-content:deploy':
                    $locale = $this->getRequest()->getParam('locale', 'en_US');
                    $output = $this->helperData->executeMagentoCommand('setup:static-content:deploy ' . $locale);
                    $message = __('Static content deployment completed successfully.');
                    break;
                    
                case 'deploy:mode:set':
                    $mode = $this->getRequest()->getParam('mode', 'production');
                    $output = $this->helperData->executeMagentoCommand('deploy:mode:set ' . $mode);
                    $message = __('Deployment mode set to %1 successfully.', $mode);
                    break;
                    
                default:
                    return $result->setData([
                        'success' => false,
                        'message' => __('Invalid command.')
                    ]);
            }
            
            return $result->setData([
                'success' => true,
                'message' => $message,
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

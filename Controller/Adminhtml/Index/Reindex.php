<?php
namespace Storeteam\AdminTools\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Storeteam\AdminTools\Helper\Data as HelperData;
use Magento\Framework\Indexer\IndexerRegistry;

class Reindex extends Action
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
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param HelperData $helperData
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        HelperData $helperData,
        IndexerRegistry $indexerRegistry
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helperData = $helperData;
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Storeteam_AdminTools::index_management');
    }

    /**
     * Reindex action
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
            $indexerId = $this->getRequest()->getParam('indexer_id');
            
            if ($indexerId === 'all') {
                $output = $this->helperData->executeMagentoCommand('indexer:reindex');
                $message = __('All indexes have been rebuilt successfully.');
            } else {
                $indexer = $this->indexerRegistry->get($indexerId);
                $indexer->reindexAll();
                $message = __('Index %1 has been rebuilt successfully.', $indexer->getTitle());
                $output = '';
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

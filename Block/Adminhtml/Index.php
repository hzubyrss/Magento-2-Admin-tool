<?php
namespace Storeteam\AdminTools\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Indexer\ConfigInterface;
use Storeteam\AdminTools\Helper\Data as HelperData;

class Index extends Template
{
    /**
     * @var ConfigInterface
     */
    protected $indexerConfig;
    
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param ConfigInterface $indexerConfig
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigInterface $indexerConfig,
        HelperData $helperData,
        array $data = []
    ) {
        $this->indexerConfig = $indexerConfig;
        $this->helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * Get indexer list
     *
     * @return array
     */
    public function getIndexers()
    {
        return $this->indexerConfig->getIndexers();
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->helperData->isEnabled();
    }

    /**
     * Should confirm execution
     *
     * @return bool
     */
    public function shouldConfirmExecution()
    {
        return $this->helperData->shouldConfirmExecution();
    }
}

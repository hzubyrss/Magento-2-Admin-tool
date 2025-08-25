<?php
namespace Storeteam\AdminTools\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Storeteam\AdminTools\Helper\Data as HelperData;
use Magento\Framework\App\DeploymentConfig;

class System extends Template
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var DeploymentConfig
     */
    protected $deploymentConfig;

    /**
     * @param Context $context
     * @param HelperData $helperData
     * @param DeploymentConfig $deploymentConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        DeploymentConfig $deploymentConfig,
        array $data = []
    ) {
        $this->helperData = $helperData;
        $this->deploymentConfig = $deploymentConfig;
        parent::__construct($context, $data);
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

    /**
     * Get current deployment mode
     *
     * @return string
     */
    public function getCurrentMode()
    {
        return $this->deploymentConfig->get('MAGE_MODE') ?: 'default';
    }
}

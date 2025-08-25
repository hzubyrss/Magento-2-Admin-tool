<?php
namespace Storeteam\AdminTools\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Shell;
use Magento\Framework\Filesystem\DirectoryList;

class Data extends AbstractHelper
{
    /**
     * @var Shell
     */
    protected $shell;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @param Context $context
     * @param Shell $shell
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        Shell $shell,
        DirectoryList $directoryList
    ) {
        $this->shell = $shell;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            'admintools/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get PHP binary path
     *
     * @return string
     */
    public function getPhpPath()
    {
        $configPath = $this->scopeConfig->getValue(
            'admintools/general/php_path',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        return !empty($configPath) ? $configPath : 'php';
    }

    /**
     * Should confirm execution
     *
     * @return bool
     */
    public function shouldConfirmExecution()
    {
        return $this->scopeConfig->isSetFlag(
            'admintools/security/confirm_execution',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Execute shell command
     *
     * @param string $command
     * @return string
     */
    public function executeCommand($command)
    {
        try {
            $output = $this->shell->execute($command);
            return $output;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get Magento root path
     *
     * @return string
     */
    public function getMagentoRootPath()
    {
        return $this->directoryList->getRoot();
    }

    /**
     * Execute Magento CLI command
     *
     * @param string $command
     * @return string
     */
    public function executeMagentoCommand($command)
    {
        $phpPath = $this->getPhpPath();
        $magentoRoot = $this->getMagentoRootPath();
        $fullCommand = sprintf('%s %s/bin/magento %s', $phpPath, $magentoRoot, $command);
        
        return $this->executeCommand($fullCommand);
    }
}

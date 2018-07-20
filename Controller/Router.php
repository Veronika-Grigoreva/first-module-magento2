<?php

namespace First\Module\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Route\ConfigInterface;
use Magento\Framework\App\Router\ActionList;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ActionList
     */
    private $actionList;

    /**
     * @var ConfigInterface
     */
    private $routeConfig;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ActionFactory $actionFactory
     * @param ActionList $actionList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $routeConfig
     */
    public function __construct(
        ActionFactory $actionFactory,
        ActionList $actionList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ConfigInterface $routeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->actionList    = $actionList;
        $this->routeConfig   = $routeConfig;
        $this->scopeConfig   = $scopeConfig;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        $url = trim($request->getPathInfo(), '/');

        $temp = $this->scopeConfig->getValue('helloworld/general/hw_input');
        if ($url !== $temp) {
            return null;
        }

        $modules = $this->routeConfig->getModulesByFrontName('firstmodule');
        if (!($modules)) {
            return null;
        }

        $request->setModuleName('firstmodule')->setControllerName('module')->setActionName('module');
        $actionInstance = $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            [
                'request' => $request
            ]
        );
        return $actionInstance;
    }

}
<?php

namespace ZF2TumblrAPI;

use Zend\ModuleManager\ModuleManager,
	Zend\ModuleManager\Feature\AutoloaderProviderInterface,
	Zend\EventManager\EventInterface as Event;
	
class Module implements AutoloaderProviderInterface
{
	protected $config = array(
		'consumerKey' => '',
		'consumerSecret' => ''
	);

	public function onBootstrap(MvcEvent $e)
	{
		$config = $e->getApplication()->getServiceManager()->get('config');

		$this->config = $config['ZF2TumblrAPI'];
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig()
    {
    	if ($this->config['consumerKey'] === '' || $this->config['consumerSecret'] === '')
    	{
    		throw new \Exception ('ZF2TumblrAPI configs are missing');
    	}

    	$tublerClient = new \Tumblr\API\Client($this->config['consumerKey'], $this->config['consumerSecret']);

    	return array(
    		'services' => array(
    			'ZF2TumblrAPI' => $tublerClient
    		)
    	);
    }
}
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

	public function onBootstrap(Event $e)
	{
		$app = $e->getApplication();
		$config = $e->getApplication()->getServiceManager()->get('config');

		$this->config = $config['ZF2TumblrAPI'];

		if ($this->config['consumerKey'] === '' || $this->config['consumerSecret'] === '')
		{
			throw new \Exception ('ZF2TumblrAPI configs are missing');
		}

		$tublerClient = new \Tumblr\API\Client($this->config['consumerKey'], $this->config['consumerSecret']);
		$app->getServiceManager()->setService('ZF2TumblrAPI', $tublerClient);
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
}
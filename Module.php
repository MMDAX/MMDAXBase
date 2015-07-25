<?php

namespace MMDAXBase;

use MMDAXBase\Service\Mail as ServiceMail;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mail\Transport\Smtp as TransportSmtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MMDAXBase\Service\Mail' => function ($sm) {
                    $config = $sm->get('Config');
                    $options = new SmtpOptions($config['mail']['smtp_options']);
                    $transportSmtp = new TransportSmtp();
                    $transportSmtp->setOptions($options);
                    $view = $sm->get('view');
                    $viewModel = new ViewModel();
                    $message = new Message;
                    $mimePart = new MimePart;
                    $mimeMessage = new MimeMessage;

                    return new ServiceMail($transportSmtp, $view, $viewModel, $message, $mimePart, $mimeMessage);
                },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'yesNo' => function ($sm) {
                    $helper = new View\Helper\YesNo();
                    return $helper;
                }
            )
        );
    }

}

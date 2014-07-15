<?php
/**
 * Factory permettant d'inhecter une instance de 
 * 
 * @package ToolBox
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 */

namespace Application\Factory;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config'); 
        $transport = \Swift_SmtpTransport::newInstance(
            $config['mail']['transport']['options']['host'], 
            $config['mail']['transport']['options']['port'], 
            $config['mail']['transport']['options']['connection_config']['ssl']
        )   ->setUsername($config['mail']['transport']['options']['connection_config']['username'])
            ->setPassword($config['mail']['transport']['options']['connection_config']['password']);
        $mailer = \Swift_Mailer::newInstance($transport);
        return $mailer;
    }
}

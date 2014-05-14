Skeleton & Doctrine & Jenkins CI & MultipleDB & Translation
===========================================================

Introduction
------------
This is a Zf2 Skeleton application with Doctrine and multiple database implementation (2 Entity Manager), this skelton use also Jenkins for CI. And translation FR to GB(or more if you want).
Unit tests are centralize in phpunit.xml on tests root directory. Feel free to fork this skeleton :)

The aim of this skeleton is to provide a good start for a complex project and provide more functionalities than the original getting started.

If you have a question, an idea, or even a correction to make to this project feel free to make a PR. This repo is far from perfect. Enjoy with it anyway :)

Installation
------------

Run `php composer.phar for install` vendor's library and set params for your 2 Databases.

here's the code (generic) for configure Doctrine with 2 Entity Manager

```php
return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params' => array(
          'host'     => 'localhost',
          'port'     => '3306',
          'user'     => 'root',
          'password' => '',
          'dbname'   => 'main_DB',
          'charset' => 'UTF8',
          'driverOptions' => array (1002 => 'SET NAMES utf8'),
        )
      ),
      'other_DB' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params' => array(
          'host'     => 'localhost',
          'port'     => '3306',
          'user'     => 'root',
          'password' => '',
          'dbname'   => 'other_DB',
          'charset'  => 'UTF8',
          'driverOptions' => array (1002 => 'SET NAMES utf8'),
        ),
      ),
    ),
    'configuration' => array(
      'orm_default' => array(
          'metadata_cache'    => 'array',
          'query_cache'       => 'array',
          'result_cache'      => 'array',
          'hydration_cache'   => 'array',
          'driver'            => 'orm_default',
          'generate_proxies'  => true,
          'proxy_dir'         => 'data/DoctrineORMModule/Proxy',
          'proxy_namespace'   => 'DoctrineORMModule\Proxy',
          'filters'           => array()
      ),
      'other_DB' => array(
          'metadata_cache'    => 'array',
          'query_cache'       => 'array',
          'result_cache'      => 'array',
          'hydration_cache'   => 'array',
          'driver'            => 'other_DB',
          'generate_proxies'  => true,
          'proxy_dir'         => 'data/DoctrineORMModule/Proxy',
          'proxy_namespace'   => 'DoctrineORMModule\Proxy',
          'filters'           => array()
      )
    ),
    'driver' => array(
        'Application_Driver' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'array',
            'paths' => array(
                __DIR__ . '/../src/Application/Entity'
            )
        ),
        'other_DB' => array(
            'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
            'drivers' => array(
                'Application\Entity' =>  'Application_Driver'
            )
        ),
    ),
    'entitymanager' => array(
        'other_DB' => array(
            'connection'    => 'other_DB',
            'configuration' => 'other_DB'
        )
    ),
    'eventmanager' => array(
        'other_DB' => array()
    ),
    'sql_logger_collector' => array(
        'other_DB' => array(),
    ),
    'entity_resolver' => array(
        'other_DB' => array()
    ),
  )
);
```

Create both databases

Run this command line to generate tables :
`php public/index.php orm:schema-tool:update --force`

And here the code for configure factories in you application Module.php file :
```php
 public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.connection.other_DB'           => new \DoctrineORMModule\Service\DBALConnectionFactory('other_DB'),
                'doctrine.configuration.other_DB'        => new \DoctrineORMModule\Service\ConfigurationFactory('other_DB'),
                'doctrine.entitymanager.other_DB'        => new \DoctrineORMModule\Service\EntityManagerFactory('other_DB'),
                'doctrine.driver.other_DB'               => new \DoctrineModule\Service\DriverFactory('other_DB'),
                'doctrine.eventmanager.other_DB'         => new \DoctrineModule\Service\EventManagerFactory('other_DB'),
                'doctrine.entity_resolver.other_DB'      => new \DoctrineORMModule\Service\EntityResolverFactory('other_DB'),
                'doctrine.sql_logger_collector.other_DB' => new \DoctrineORMModule\Service\EntityResolverFactory('other_DB'),
                'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function(\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                    return new \DoctrineORMModule\Form\Annotation\AnnotationBuilder($sl->get('doctrine.entitymanager.other_DB'));
                },
            ),
        );
    }
```

For retrieving your EntityManager Everywhere use :

```php
$this->em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');

```
OR :

```php

$this->em = $this->getServiceManager()->get('doctrine.entitymanager.other_DB');

```
Translation
-----------
This skeleton can handle translation in french to english (or more depends to you) with session(feel free to modify this with route if you prefer).
So, don't forget to download PoEdit software for translate all your module.
This skeleton also translate your forms. zf2 will be translate all your labels using those methodes : formSubmit, formLabel and formElement and make sure that your string are translated.

PHPunit & Jenkins
-----------------

In command line run :
`cd tests`
`phpunit`

Don't forget to add in boostrapp.php all require for each module you have.
You have to edit also the build.xml file for configuring all of your plugin in on jenkins. Full path for your project
has to be edit : 
```xml
<project name="other_project" default="main">
    <property name="basedir" value="c:/wamp/www/other_project/" /><!-- here -->
    <!-- ... -->
    
    <target name="phpunit" description="Lancement des tests unitaires">
        <echo msg="Start Unit testing" />
        <exec command="phpunit.bat --configuration=C:/wamp/www/other_project/tests/phpunit.xml"/><!-- and here -->
    </target>
    <!-- ... -->
```
How works Units tests on this Skeleton ?
--------------------------------------

In `Util` directory of each module you can find a class ServiceManagerFactory wich is used for retrieving ServiceManager for our test
This class is called on `[moduleName]Bootstrap.php` And in `Framework` directory of each module you can find a class who extends PHPUnit_Framework_TestCase or ZendTest 

You tested it ?
---------------

Give me feedback !

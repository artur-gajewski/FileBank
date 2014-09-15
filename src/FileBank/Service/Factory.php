<?php
namespace FileBank\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use FileBank\Manager;
use FileBank\Options;

/**
 * FileBank service manager factory
 */
class Factory implements FactoryInterface
{
    /**
     * Factory method for FileBank Manager service
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \FileBank\Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /* @var $params \FileBank\Options\ModuleOptions */
        $params = $serviceLocator->get(Options\ModuleOptions::getServiceKey());

        return new Manager($params->toArray(), $em);
    }
}

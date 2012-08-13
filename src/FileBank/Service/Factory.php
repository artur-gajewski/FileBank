<?php

namespace FileBank\Service;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    FileBank\Manager;

/**
* FileBank service manager factory
*/
class Factory implements FactoryInterface
{
    /**
     * Factory method for FileBank Manager service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \FileBank\Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $params = $config['FileBank']['params'];
        $doctrine = $serviceLocator->get('doctrine.entitymanager.orm_default');
        
        $manager = new Manager($params, $doctrine);
        return $manager;
    }
}
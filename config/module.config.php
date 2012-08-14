<?php

namespace FileBank;

return array(
    __NAMESPACE__ => array(
        'params' => array(
            'filebank_folder'  => '/data/filebank/', 
            'default_is_active' => true,
            'chmod'           => 0755,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\File' => __NAMESPACE__ . '\Controller\FileController',
        ),
    ),
    'router' => array(
        'routes' => array(
            __NAMESPACE__ => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/files',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\File',
                        'action'     => 'index',
                    ),
                ),
            ),
            __NAMESPACE__ . '-Download' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/files/:id',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\File',
                        'action'     => 'download',
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'username',
                    'password' => 'password',
                    'dbname'   => 'database_name',
                )
            )
        ),
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);
<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH . '/modules/ISTCIDOC/view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'ISTCIDOC\Form\ISTCIDOCForm' => 'ISTCIDOC\Form\ISTCIDOCForm',
            'ISTCIDOC\Form\ISTCIDOCFormLocation' => 'ISTCIDOC\Form\ISTCIDOCFormLocation',
        ],
        'factories' => [
            'ISTCIDOC\Form\Element\ISTCIDOCResourceClassSelect' => 'ISTCIDOC\Service\Form\Element\ISTCIDOCResourceClassSelectFactory',
        ],
    ],
    'entity_manager' => [
        'mapping_classes_paths' => [
            OMEKA_PATH . '/modules/ISTCIDOC/src/Entity',
        ],
        'proxy_paths' => [
            OMEKA_PATH . '/modules/ISTCIDOC/data/doctrine-proxies',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'dataTypeCIDOC' => 'ISTCIDOC\Service\ViewHelper\DataTypeCIDOCFactory',
            'ISTCIDOCpropertySelect' => 'ISTCIDOC\Service\ViewHelper\ISTCIDOCPropertySelectFactory',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'ISTCIDOC\DataTypeManager' => 'ISTCIDOC\Service\DataTypeManagerFactoryCIDOC',
        ],
    ],
    'data_types' => [
        'abstract_factories' => ['ISTCIDOC\Service\LocationFactory'],
        'invokables' => [
            'literal' => 'ISTCIDOC\DataType\Literal',
            'uri' => 'ISTCIDOC\DataType\Uri',
            'location' => 'ISTCIDOC\DataType\Location',
            'resource_cidoc' => 'ISTCIDOC\DataType\Resource\All',
            'resource:item' => 'ISTCIDOC\DataType\Resource\Item',
            'resource:itemset' => 'ISTCIDOC\DataType\Resource\ItemSet',
            'resource:media' => 'ISTCIDOC\DataType\Resource\Media',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'ISTCIDOC\Controller\Site\Item' => ISTCIDOC\Controller\Site\ItemController::class,
            'ISTCIDOC\Controller\Site\Location' => ISTCIDOC\Controller\Site\LocationController::class,
            'ISTCIDOC\Controller\Site\Index' => ISTCIDOC\Controller\Site\IndexController::class,
            'ISTCIDOC\Controller\Site\ItemSet' => ISTCIDOC\Controller\Site\ItemSetController::class,
            'ISTCIDOC\Controller\Site\Media' => ISTCIDOC\Controller\Site\MediaController::class,
            'ISTCIDOC\Controller\Site\Page' => ISTCIDOC\Controller\Site\PageController::class,
        ],
        'factories' => [
            'ISTCIDOC\Controller\Index' => 'ISTCIDOC\Service\Controller\IndexControllerFactory',
            'ISTCIDOC\Controller\Location' => 'ISTCIDOC\Service\Controller\LocationControllerFactory',
        ],
    ],
    'api_adapters' => [
        'invokables' => [
            'Omeka\Api\Adapter\ItemAdapter' => 'ISTCIDOC\Api\Adapter\NewItemAdapter',
            'istcidoc_items' => 'ISTCIDOC\Api\Adapter\ISTCIDOCItemAdapter',
            'locations' => 'ISTCIDOC\Api\Adapter\LocationAdapter',
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'type' => \Zend\Router\Http\Segment::class,
                'options' => [
                    'route' => '/s/:site-slug',
                    'constraints' => [
                        'site-slug' => '[a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'Omeka\Controller\Site',
                        '__SITE__' => true,
                        'controller' => 'Index',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'resource' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/:controller[/:action]',
                            'defaults' => [
                                'action' => 'browse',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                    'resource-id' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/:controller/:id[/:action]',
                            'defaults' => [
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+',
                            ],
                        ],
                    ],
                    'item' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/item[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'ISTCIDOC\Controller\Site',
                                'controller' => 'Item',
                                'action' => 'browse',
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                    'item-id' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/item/:id[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'ISTCIDOC\Controller\Site',
                                'controller' => 'Item',
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+',
                            ],
                        ],
                    ],
                    'location-id' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/location/:id[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'ISTCIDOC\Controller\Site',
                                'controller' => 'Location',
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+',
                            ],
                        ],
                    ],
                ],
            ],
            'admin' => [
                'child_routes' => [
                    'ist-cidoc' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/ist-cidoc',
                            'defaults' => [
                                '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                'controller' => 'Index',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'id' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/location/:id[/:action]',
                                    'constraints' => [
                                        //'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '\d+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Location',
                                        'action' => 'show',
                                    ],
                                ],
                            ],
                            'resource-id' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/item/:id[/:action]',
                                    'constraints' => [
                                        //'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '\d+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Index',
                                        'action' => 'show',
                                    ],
                                ],
                            ],
                            'browse-location' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/browse-location',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                        'controller' => 'Location',
                                        'action' => 'browse',
                                    ],
                                ],
                            ],
                            'add-location' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/add-location',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                        'controller' => 'Location',
                                        'action' => 'addLocation',
                                    ],
                                ],
                            ],
                            'browse' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/browse',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                        'controller' => 'Index',
                                        'action' => 'browse',
                                    ],
                                ],
                            ],
                            'sidebar-select' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/sidebar-select',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                        'controller' => 'Index',
                                        'action' => 'sidebar-select',
                                    ],
                                ],
                            ],
                            'location-search' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/location-search',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'ISTCIDOC\Controller',
                                        'controller' => 'Location',
                                        'action' => 'search',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'IST CIDOC', // @translate
                'route' => 'admin/ist-cidoc',
                'resource' => 'ISTCIDOC\Controller\Index',
                'pages' => [
                    [
                        'label' => 'Add IST CIDOC Item', // @translate
                        'route' => 'admin/ist-cidoc',
                        'resource' => 'ISTCIDOC\Controller\Index',
                    ],
                    [
                        'label' => 'Browse IST CIDOC Item', // @translate
                        'route' => 'admin/ist-cidoc/browse',
                        'resource' => 'ISTCIDOC\Controller\Index',
                        'controller' => 'Index',
                        'action' => 'browse',
                    ],
                    [
                        'label' => 'Add New Location', // @translate
                        'route' => 'admin/ist-cidoc/add-location',
                        'resource' => 'ISTCIDOC\Controller\Location',
                        'controller' => 'Location',
                        'action' => 'addLocation',
                    ],
                    [
                        'label' => 'Browse Locations', // @translate
                        'route' => 'admin/ist-cidoc/browse-location',
                        'resource' => 'ISTCIDOC\Controller\Location',
                        'controller' => 'Location',
                        'action' => 'browse',
                    ],
                    [
                        'label' => 'Location search', // @translate
                        'route' => 'admin/ist-cidoc/location-search',
                        'resource' => 'ISTCIDOC\Controller\Location',
                        'controller' => 'Location',
                        'action' => 'search',
                    ],
                    [
                        'route' => 'admin/ist-cidoc/location/id',
                        'resource' => 'ISTCIDOC\Controller\Location',
                        'controller' => 'Location',
                        'action' => 'show',
                        'visible' => false,
                    ],
					[
                        'route' => 'admin/ist-cidoc/item/id',
						'resource' => 'ISTCIDOC\Controller\Index',
                        'controller' => 'Index',
                        'action' => 'show',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
];

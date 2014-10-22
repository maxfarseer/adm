<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'zoloto.ru',
    'sourceLanguage' => 'en_US',
    'language' => 'ru',
	
	// preloading 'log' component
	'preload'=>array(
		//'bootstrap',
		'log',),
	
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
        'admin',
        'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(

        'instagram' => array(
            'class' => 'ext.yiinstagram.InstagramEngine',
            'config' => array (
                'client_id' => 'bbacdc4fe1304bf2969af5054fe54e27',
                'client_secret'   => '4565260cf1d5452d986ee8d93bef9ea3',
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'http://www.zolotorusskikh.ru/site/insta',
            )
        ),

		'user'=>array(
			// enable cookie-based authentication
			//'allowAutoLogin'=>true,
			'class' => 'WebUser',
		),
		'ih'=>array(
			'class'=>'CImageHandler',
		),
		'cache'=>array('class'=>'system.caching.CFileCache'),
		    
		'email'=>array(
			'class'=>'application.extensions.email.Email',
			'delivery'=>'php', //Will use the php mailing function.  
			//May also be set to 'debug' to instead dump the contents of the email into the view
		),

        'bootstrap' => array(
            'class' => 'application.components.yiibooster.components.Bootstrap',
        ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => true,
			//'useStrictParsing'=>true,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=falsetrue',
			'emulatePrepare' => true,
			'username' => 'falsetrue',
			'password' => 'SusaMosusa',
			'charset' => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		
		//менеджер авторизации
		'authManager' => array(
			'class' => 'PhpAuthManager',
			'defaultRoles' => array('guest'),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'nikozor@bk.ru',
		'imgPath'=> 'images/products/',
		'imgBlog'=> 'images/blog/',
		'slidePath'=> 'images/slider/',
		'filter'=> array('5','6','7','9'),
		'colors'=>array('#fff'=>'Белый','#feddbd'=>'Бежевый','#ff6666'=>'Красный','#ffb4d0'=>'Розовый'),
		'cache'=>0,

	),
);
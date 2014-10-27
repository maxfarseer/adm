<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
//    require(__DIR__ . '/../_config.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=_adm',
                'username' => 'root',
                'password' => '123',
                'tablePrefix'=>'adm_',
                'charset' => 'utf8',
            ],
        ],
    ]
);

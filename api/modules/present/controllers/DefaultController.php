<?php
namespace app\modules\present\controllers;

use yii\base\Exception;
use app\modules\user\models\User;
use app\modules\present\models\Present;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use app\helpers\ExeptionJSON;

use yii\rest\ActiveController;
class DefaultController extends ActiveController
{

    public $enableCsrfValidation = false;

    public  $modelClass  =  'app\modules\user\models\User' ;
    public  $serializer  =  [
        'class'  =>  'yii\rest\Serializer' ,
        'collectionEnvelope'  =>  'items' ,
    ];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['signupq'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => ['login'],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'userinfo' => ['post'],
//                ],
//            ],
        ];
    }



}
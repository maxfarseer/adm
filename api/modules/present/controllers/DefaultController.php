<?php
namespace app\modules\present\controllers;

use yii\base\Exception;
use app\modules\user\models\User;
use app\modules\user\models\DataFormat;
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
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                    [
//                        'actions' => ['signupq'],
//                        'allow' => true,
//                        'roles' => ['admin'],
//                    ],
//                ],
//            ],
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

//    public function beforeAction($action){
//
//        if (parent::beforeAction($action)) {
//
//        } else
//            return false;
//    }
    /*
     * get pkg user
     */
    public function actionUserpkg()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('GET');
        $data = Present::usrPkg();

        $answer['data'] = ['real_client' => $data];
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * get digit user
     */
    public function actionUserdigit()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('GET');
        $f_name = Present::usrDigit();

        $answer['data'] = ['virtual_client' => $f_name];
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * get send msg
     */
    public function actionDigitmsg()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        Present::addMessage('digit',Yii::$app->request->post('data'));

        $answer['data'] = ['Поздравление отправлено'];
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * get send comment
     */
    public function actionPkgcomment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        Present::addComment('pkg',Yii::$app->request->post('data'));

        $answer['data'] = ['Комментарий оставлен'];
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }
}
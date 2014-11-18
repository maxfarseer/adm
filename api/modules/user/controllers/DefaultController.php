<?php
namespace app\modules\user\controllers;

use app\modules\present\models\Present;
use app\modules\user\models\DataFormat;
use app\modules\user\models\LoginForm;
use yii\base\Exception;
use app\modules\user\models\User;
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

    public function actionLogin()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');

        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try {
                if ($eauth->authenticate()) {
//                  var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
        ///////////

        if (!Yii::$app->user->isGuest)
            throw new ExeptionJSON('Уже авторизован!', ExeptionJSON::STATUS_BAD);

        $model = new LoginForm();

        if (!Yii::$app->request->isPost)
            throw new ExeptionJSON('Неверный метод!', ExeptionJSON::STATUS_BAD);

        $attr = Yii::$app->request->post();
        $model->username = $attr['email'];
        $model->password = $attr['pass'];

            if(!$model->login())
                throw new ExeptionJSON('Авторизационные данные не верны или бан!', ExeptionJSON::STATUS_BAD);

            $answer['data'] = 'Авторизован успешно';
            $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * user logout
     */
    public function actionLogout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        User::logoutUsr();

        $answer['data'] = 'Logout true';
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * All Users
     */
    public function actionAllusers()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = User::find()
            ->select(['f_name','s_name'])
            ->asArray()
            ->all();

        $answer['data'] = $model;
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * get user info or false
     */
    public function actionUserinfo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('GET');

        $model = User::getInfo();

        $answer['data'] = $model;
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * Update user info
     */
    public function actionUserupt()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        $model = User::uptInfo(Yii::$app->request->post('user'));

        $answer['data'] = 'Данные изменены';
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * registration
     */
    public function actionSignup()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        $attr = Yii::$app->request->post();
        User::signUsr($attr);

        $answer['data'] = 'OK';
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * baned
     */
    public function actionBan()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        $attr = Yii::$app->request->post();
        User::userBan($attr['id']);

        $answer['data'] = 'OK';
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * baned
     */
    public function actionBanout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        DataFormat::reqRevision('POST');

        $attr = Yii::$app->request->post();
        User::userBan($attr['id'],1);

        $answer['data'] = 'OK';
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

    /*
     * test method api
     */
    public function actionTest()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = User::find();

        $answer['data']['BD'] = $model?'YES':'NO';
        $answer['data']['GET'] = Yii::$app->request->get();
        $answer['data']['POST'] = Yii::$app->request->post();
        $answer['status'] = ExeptionJSON::STATUS_OK;

        return $answer;
    }

}
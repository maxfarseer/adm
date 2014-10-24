<?php
namespace app\modules\user\controllers;

use app\modules\user\models\LoginForm;
use yii\base\Exception;
use app\modules\user\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\Response;
class DefaultController extends Controller
{
    const STATUS_OK = 200;
    const STATUS_BAD = 0;

    public $enableCsrfValidation = false;

//    public function beforeAction($action){
//        parent::beforeAction($action);
//        Yii::$app->response->format = Response::FORMAT_JSON;
//    }

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
            'eauth' => array(
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => array('login'),
            ),
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
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

       try{
            if (!Yii::$app->user->isGuest)
                throw new Exception('Уже авторизован!', self::STATUS_BAD);

            $model = new LoginForm();

            if (Yii::$app->request->post()) {

                $attr = Yii::$app->request->post();
                $model->username = $attr['email'];
                $model->password = $attr['pass'];

                if($model->login()){
                    $answer['data'] = 'Авторизован успешно';
                    $answer['status'] = self::STATUS_OK;
                } else
                    throw new Exception('Авторизационные данные не верны!', self::STATUS_BAD);
            } else
                throw new Exception('Авторизация не прошла!', self::STATUS_BAD);

        } catch (Exception $e) {
            $answer['data'] = $e->getMessage();
            $answer['status'] = $e->getCode();
        }

        return $answer;
    }

    public function actionLogout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(Yii::$app->user->logout())
            {
                $answer['data'] = 'Logout true';
                $answer['status'] = self::STATUS_OK;
            } else
                throw new Exception('Logout false', self::STATUS_BAD);
        } catch (Exception $e) {
            $answer['data'] = $e->getMessage();
            $answer['status'] = $e->getCode();
        }

        return $answer;
    }

    public function actionSignup()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->post()) {

                $model = new User;

                $attr = Yii::$app->request->post();
                $model->email = $attr['email'];
                $model->pass = $attr['pass'];

                $model->setScenario('signup');

                if ($model->validate()) {

                    $model->pass = $model->generatePassword($model->pass);
                    $model->role = 'user';
                    $model->save(false);

                    $auth = Yii::$app->authManager;
                    $adminRole = $auth->getRole('user');
                    $auth->assign($adminRole, $model->getId());

                    $answer['data'] = 'OK';
                    $answer['status'] = self::STATUS_OK;
                } else {

                    $errors = json_encode($model->getErrors(), JSON_FORCE_OBJECT);
                    throw new Exception($errors, self::STATUS_BAD);
                }
            } else
                throw new Exception('Undefined query params', self::STATUS_BAD);

        } catch (Exception $e) {
            $answer['data'] = $e->getMessage();
            $answer['status'] = $e->getCode();
        }

        return $answer;
    }

}
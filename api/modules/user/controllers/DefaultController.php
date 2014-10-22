<?php
namespace app\modules\user\controllers;

use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use app\modules\admin\views;
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','signup'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
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

        $this->layout='@app/modules/admin/views/layouts/admin';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        //print_r(Yii::$app->request->post());

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->setScenario('signup');
            if($model->validate()){
                $model->pass=$model->generatePassword($model->pass);
                $model->role = 'moderator';
                $model->save(false);

                $auth = Yii::$app->authManager;
                $adminRole = $auth->getRole('user');
                $auth->assign($adminRole, $model->getId());
            }else{
                Yii::$app->session->setFlash('error', $model->getErrors());
                return $this->refresh();
            }
            return $this->goBack();
        } else {
            return $this->render('signup', [
                'model' => $model,
            ]);
        }
    }
}
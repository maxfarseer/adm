<?php

namespace app\modules\main\controllers;

use Yii;
use app\helpers\LoaderFH;
use yii\base\ErrorException;
use app\modules\user\models\User;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /*
     * All points in map
     */
    public function actionIndex($id=false)
    {
        print_r(User::find()
            ->select('email')
            ->where(['id'=>Yii::$app->user->id])
            ->asArray()
            ->one());

        return $this->render('index');
    }

    /*
     * dump
     */
    public function actionDump()
    {
        try {
            $f = @fopen(BASE_PATH . '/dump/5-dump.sql', "r");
            if ($f) {
                $q = '';

                while (!feof($f)) {
                    // читаем построчно в буфер $q
                    $q .= fgets($f);

                    if (substr(rtrim($q), -1) == ';') {

                        Yii::$app->db->createCommand($q)->execute();

                        // обнуляем буфер
                        $q = '';
                    }
                }
            }
        } catch (Exception $e){
            return $e->getMessage();
        }

        return "Все ОК!";
    }
    /*
     * Get point from id
     */
    public function actionPoint()
    {
        $answer=array();
        try {
            //Yii::$app->request->isAjax &&
            if($id=Yii::$app->request->post('id')){

                $point = Point::autor_layer($_POST['id']);

                if($point){
                    $answer['result'] = '1';
                    $answer['data']['point']= $point;}
                else
                    throw new ErrorException('Точка не найдена',0);

            }else
                throw new ErrorException('Только POST',0);


        } catch (ErrorException $e) {
            $answer['msg'] = $e->getMessage();
            $answer['result'] = '0';
        }

        echo json_encode($answer);
    }

   /*
    * Get point from id
    */
    public function actionLike()
    {
        $answer=array();
        try {

            //Yii::$app->request->isAjax &&
            if($id=Yii::$app->request->post('id')){

                if(isset($_COOKIE['MGooglePool'])){
                    $balls=json_decode($_COOKIE['MGooglePool']);

                    if(!in_array($id,$balls)){
                        $balls[]=$id;
                        setcookie('MGooglePool', json_encode($balls), time()+60*60*24*90, '/', '', false, true);
                    }
                    else
                        throw new ErrorException('Уже голосовал',0);
                }else
                    setcookie('MGooglePool', json_encode([$id]), time()+60*60*24*90, '/', '', false, true);

                if(Point::updateAllCounters(['like'=>1],['id'=>$id]))
                    $answer['result'] = '1';
                else
                    throw new ErrorException('Ошибка сохранения',0);

            }else
                throw new ErrorException('Только POST',0);


        } catch (ErrorException $e) {
            $answer['msg'] = $e->getMessage();
            $answer['result'] = '0';
        }

        echo json_encode($answer);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

}
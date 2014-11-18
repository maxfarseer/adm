<?php

namespace app\modules\present\models;

use Yii;
use app\helpers\ExeptionJSON;
use app\modules\user\models\User;

/**
 * This is the model class for table "adm_present".
 *
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property string $date
 * @property string $type
 * @property integer $status
 */
class Present extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%present}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'status'], 'integer'],
            [['date'], 'safe'],
            [['type'], 'string', 'max' => 10],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'date' => 'Date',
            'type' => 'Type',
            'status' => 'Status',
            'message' => 'сообщение',
        ];
    }

    public static function statusPresent($status) {

        $stat = [
            '0' => 'free',
            '1' => 'verifying',
            '2' => 'blocked',
        ];

        return $stat[$status];
    }

    /**
     * get digit user
     */
    public static function usrDigit()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        if(!User::checkAccessPresent('digit'))
            throw new ExeptionJSON('Не заполнены необходимые поля анкеты', ExeptionJSON::STATUS_ERROR);

        if(Yii::$app->user->identity->id_digit != 0)
            throw new ExeptionJSON('Поздравитель уже получен', ExeptionJSON::STATUS_ERROR);

        $tbl = User::tableName();
        $model = User::find()
            ->select([$tbl.'.id',$tbl.'.email',$tbl.'.f_name'])

            ->joinWith(['digit'])

            ->where(['AND',
                [$tbl.'.status_digit' => '1'],
                ['<>', $tbl.'.id', Yii::$app->user->id],
                ['<>', $tbl.'.status', '0'],
                ['digit.id_digit' => null]])
            ->orderBy('RAND()')
            ->one();

        if(!$model)
            throw new ExeptionJSON('На данный момент нет претендентов на получение подарка', ExeptionJSON::STATUS_ERROR);

        if(Present::createPresent($model->id,'digit'))
            User::updateAll(['id_digit' => $model->id],['id' => Yii::$app->user->id]);

        return $model->nickname;
    }

    /**
     * get pkg user
     */
    public static function usrPkg()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        if(!User::checkAccessPresent('pkg'))
            throw new ExeptionJSON('Не заполнены необходимые поля анкеты', ExeptionJSON::STATUS_ERROR);

        if(Yii::$app->user->identity->id_pkg != 0)
            throw new ExeptionJSON('Поздравитель уже получен', ExeptionJSON::STATUS_ERROR);

        $tbl = User::tableName();
        $model = User::find()
            ->select([$tbl.'.id',$tbl.'.s_name',$tbl.'.f_name',$tbl.'.address'])

            ->joinWith(['pkg'])

            ->where(['AND',
                [$tbl.'.status_pkg' => '2'],
                ['<>', $tbl.'.id', Yii::$app->user->id],
                ['<>', $tbl.'.status', '0'],
                ['pkg.id_pkg' => null]])
            ->orderBy('RAND()')
            ->one();

        if(!$model)
            throw new ExeptionJSON('На данный момент нет претендентов на получение подарка', ExeptionJSON::STATUS_ERROR);

        if(Present::createPresent($model->id))
            User::updateAll(['id_pkg' => $model->id],['id' => Yii::$app->user->id]);

        return [$model->f_name, $model->s_name, $model->address];
    }

    public static function createPresent($id, $type = 'pkg'){

        $present = new Present();
        $present -> from = Yii::$app->user->id;
        $present -> to = $id;
        $present -> type = $type;
        $present -> status = 0;
        $present -> date = date('Y-m-d H:i:s');

        return $present -> save();
    }

    public static function addMessage($type = 'pkg',$message = '') {

        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        $to = 'id_'.$type;

        if(empty(Yii::$app->user->$to))
                throw new ExeptionJSON('Некому писать сообщение', ExeptionJSON::STATUS_ERROR);

        $answer = Present::updateAll(['message' => $message],['from' => Yii::$app->user->id, 'to' => $to,'type' => $type]);

        if($answer == 0)
            throw new ExeptionJSON('Сообщение не отправилось', ExeptionJSON::STATUS_ERROR);

        return $answer;
    }

    public static function addComment($type = 'pkg',$comment = '') {

        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        $from = 'id_'.$type;

        if(empty(Yii::$app->user->$from))
            throw new ExeptionJSON('Некому писать комментарий', ExeptionJSON::STATUS_ERROR);

        $answer = Present::updateAll(['message' => $comment],['from' => $from, 'to' => Yii::$app->user->id,'type' => $type]);

        if($answer == 0)
            throw new ExeptionJSON('Сообщение не отправилось', ExeptionJSON::STATUS_ERROR);

        return $answer;
    }

}
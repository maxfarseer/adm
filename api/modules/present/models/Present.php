<?php

namespace app\modules\present\models;

use Yii;
use app\helpers\ExeptionJSON;

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
            [['type'], 'string', 'max' => 10]
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
        ];
    }
}
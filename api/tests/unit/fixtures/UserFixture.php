<?php
namespace app\tests\unit\fixtures;

use app\modules\user\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\modules\user\models\User';
}
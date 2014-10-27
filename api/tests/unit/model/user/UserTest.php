<?php

namespace app\tests\unit\model;

use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\codeception\DbTestCase;
use app\tests\unit\fixtures\UserFixture;

class UserTest extends DbTestCase
{
    protected $model;

    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }


    /**
//     * @dataProvider providerGetUser
     */
    public function testApprove($email, $pass, $status)
    {
        $this->assertTrue(true);
    }

    public function providerGetUser(){
        return [
            [0, 0, false],
            [0, 1, 1],
            [1, 'yy', 1],
            [1, 'u', '']
        ];
    }

}

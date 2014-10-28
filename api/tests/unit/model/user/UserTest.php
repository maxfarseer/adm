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

    /*
     * login user
     */
    public function UserLogin()
    {
        $model = new LoginForm();

        $model-> username = 'nikozor@ya.ru';
        $model-> password = '123';

        $this->assertTrue($this->isGuest());
        $this->assertTrue($model->login());
        $this->assertFalse($this->isGuest());

    }

    /*
     * getuser info
     */
    public function testInfoUser()
    {
        $this->UserLogin();

        $attr = ['email'=>'nikozor@ya.ru','f_name'=>'Никита','s_name'=>'Зорин','address'=>'Киров'];
        $model = User::getInfo();

        $this->assertEquals($attr,$model);

        \Yii::$app->user->logout();
        $model = User::getInfo();

        $this->assertFalse($model);

    }

    /*
     * update userinfo
     * @dataprovider providerUserInfo
     */
    public function testUpdateUserInfo($attr,$rez)
    {
        $this->UserLogin();

        $answer = User::UptUserInfo($attr);
        $this->assertEqals($answer,$rez);

        \Yii::$app->user->logout();

        $answer = User::UptUserInfo($attr);
        $this->assertEqals($answer,$rez);

    }
    /**
     * @dataProvider providerGetUser
     */
    public function testSave($email, $pass, $status)
    {
        $this->assertTrue(true);
    }

    public function providerGetUser(){
        return [
            [['email'=>'nikozor@ya.ru','f_name'=>'Никита','s_name'=>'Зорин','address'=>'Киров'], true],
            [0, 1, 1],
            [1, 'yy', 1],
            [1, 'u', '']
        ];
    }

    public function isGuest(){
        return \Yii::$app->user->isGuest;
    }

}

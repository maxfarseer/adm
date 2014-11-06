<?php

namespace app\tests\unit\model;

use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\base\Exception;
use yii\codeception\DbTestCase;
use app\tests\unit\fixtures\UserFixture;
use app\helpers\ExeptionJSON;

class UserTest extends DbTestCase
{
    protected $model;

    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }

    /////////// my JSONException ///////////

    /*
     * json exeption
     */
    public function assertJSONException(callable $callback, $expectedCode = NULL, $expectedMessage = NULL)
    {

        try {
            $callback();
        } catch (ExeptionJSON $e) {

            $message = $e->getMessage();
            $code = $e->getCode();

            if (NULL !== $expectedCode) {
                $this->assertEquals($expectedCode, $code, "Failed asserting code of thrown");
            }
            if (NULL !== $expectedMessage) {
                $this->assertContains($expectedMessage, $message, "Failed asserting the message of thrown");
            }
            return;
        }

        $this->fail("Failed asserting that exception was thrown.");
    }

    /////////// login user ///////////

    /*
     * login user
     */
    public function UserLogin($email='nikozor@ya.ru',$pass='123')
    {
        $model = new LoginForm();

        $model-> username = $email;
        $model-> password = $pass;

        $this->assertTrue($this->isGuest());
        $this->assertTrue($model->login());
        $this->assertFalse($this->isGuest());

    }

    /////////// get userinfo ///////////

    /*
     * get user information
     */
    public function testInfoUser()
    {
        $this->UserLogin();

        $attr = ['email'=>'nikozor@ya.ru','f_name'=>'Никита','s_name'=>'Зорин','address'=>'Киров'];
        $model = User::getInfo();

        $this->assertEquals($attr,$model);

        \Yii::$app->user->logout();
            $this->assertJSONException( function() {User::getInfo();},403, 'Авторизуйтесь!' );
    }

    /////////// update userinfo ///////////

    /**
     * update userinfo
     * @dataProvider providerUserInfo
     */
    public function testUpdateUserInfo($attr,$rez)
    {
        $this->UserLogin($attr['email'],$attr['pass']);

       $func = function() use (&$attr) {User::uptInfo($attr); };

        if ($rez!==true)
            $this->assertJSONException($func, 0, $rez );
        else {
            $answer = User::uptInfo($attr);
            $this->assertEquals($answer,$rez);
        }

        \Yii::$app->user->logout();
            $this->assertJSONException($func, 403, 'Авторизуйтесь!' );
    }

    public function providerUserInfo(){
        return [
            [['email'=>'nikozor@ya0.ru','pass'=>'123','f_name'=>'123','s_name'=>'123','address'=>'123','status'=>'0','role'=>'user'], 'пользователь забанен'],
            [['email'=>'nikozor@ya1.ru','pass'=>'123','f_name'=>'1','s_name'=>'Зорин','address'=>'Киров','status'=>'1','role'=>'user'], true],
            [['email'=>'nikozor@ya2.ru','pass'=>'123','f_name'=>'','s_name'=>'','status'=>'2','role'=>'user'], 'изменение информации запрещено'],
            [['email'=>'nikozor@ya3.ru','pass'=>'123','f_name'=>'','s_name'=>'','status'=>'3','role'=>'user'], 'изменение информации запрещено'],
        ];
    }
    //////////////////


    public function isGuest(){
        return \Yii::$app->user->isGuest;
    }

}

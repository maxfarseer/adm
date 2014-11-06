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
    public function UserLogin()
    {
        $model = new LoginForm();

        $model-> username = 'nikozor@ya.ru';
        $model-> password = '123';

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
        $this->UserLogin();

       $func = function() use (&$attr) {User::uptInfo($attr); };

        if (!$rez)
            $this->assertJSONException($func, 0, 'Ошибка обработки данных.' );
        else {
            $answer = User::uptInfo($attr);
            $this->assertEquals($answer,$rez);
        }

        \Yii::$app->user->logout();
            $this->assertJSONException($func, 403, 'Авторизуйтесь!' );
    }

    public function providerUserInfo(){
        return [
            [['f_name'=>'123','s_name'=>'123','address'=>'123','status'=>'123','role'=>'user'], true],
            [['f_name'=>'','s_name'=>'Зорин','address'=>'Киров'], false],
            [['email'=>'nikozor@ya.ru','f_name'=>'','s_name'=>'','address'=>''], false],
        ];
    }
    //////////////////


    public function isGuest(){
        return \Yii::$app->user->isGuest;
    }

}

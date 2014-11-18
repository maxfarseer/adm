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
    public function UserLogin($email='nikozor@ya.ru0',$pass='123')
    {
        \Yii::$app->user->logout();
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

        $model = User::getInfo();

        $this->assertTrue(($model['real_client'] === null) || sizeof($model['real_client']) > 0);
        $this->assertTrue(($model['virtual_client'] === null) || sizeof($model['virtual_client']) > 0);

        \Yii::$app->user->logout();
            $this->assertJSONException( function() {User::getInfo();},403, 'Авторизуйтесь!' );
    }

    /////////// update userinfo ///////////

    /**
     * update userinfo
     * @dataProvider providerUserInfo
     */
    public function testUpdateUserInfo($usr,$rez,$attr)
    {
        $this->UserLogin($usr['email'],$usr['pass']);

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
        $users = [
            [['email'=>'nikozor@ya0.ru','pass'=>'123'], 'Пользователь забанен'],
            [['email'=>'nikozor@ya1.ru','pass'=>'123'], true],
            [['email'=>'nikozor@ya2.ru','pass'=>'123'], 'Изменение информации запрещено'],
            [['email'=>'nikozor@ya3.ru','pass'=>'123'], 'Изменение информации запрещено'],
        ];

        $data = [
            [['f_name'=>'123','s_name'=>'123','address'=>'123','status'=>'0','role'=>'moderator']],
        ];

        foreach($users as $usr){

            foreach($data as $dat){
                $answer[]=array_merge($usr,$data);
            }
        }
        return [
            $answer
        ];
    }
    //////////////////


    public function isGuest(){
        return \Yii::$app->user->isGuest;
    }

}

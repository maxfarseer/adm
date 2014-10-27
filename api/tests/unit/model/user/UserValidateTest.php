<?php

namespace app\tests\unit\model;

use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\codeception\DbTestCase;
use app\tests\unit\fixtures\UserFixture;

class UserValidateTest extends DbTestCase
{
    protected $model;

    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }

    //validate User

    //Required
    public function testEmailIsRequired()
    {
        $this->AttrIsRequired('email');
    }

    public function testRoleIsRequired()
    {
        $this->AttrIsRequired('role');
    }

    public function testStatusIsRequired()
    {
        $this->AttrIsRequired('status');
    }

    public function testPassIsRequired()
    {
        $this->AttrIsRequired('pass');
    }

    //unique
    public function testEmailIsUnique()
    {
        $this->AttrIsUnique(['attr'=>'email','value'=>'888@888.888']);
    }

    //length
    public function testEmailIsLength()
    {
        $this->AttrIsLength('email',20);
    }

    public function testPassIsLength()
    {
        $this->AttrIsLength('pass',70);
    }

    public function testRoleIsLength()
    {
        $this->AttrIsLength('role',10);
    }

    //integer
    public function testRoleIsInt()
    {
        $this->AttrIsInteger('status');
    }


    /*
     * integer attribute
     */
    public function AttrIsInteger($attr)
    {
        $this->model = ($this->model!== User::className())? new User():$this->model;

        $this->model->$attr = '1.2';
        $this->assertFalse($this->model->validate(array($attr)));

        $this->model->$attr = '1';
        $this->assertTrue($this->model->validate(array($attr)));
    }

    /*
     * required attribute
     */
    public function AttrIsRequired($attr)
    {
        $this->model = ($this->model!== User::className())? new User():$this->model;
        $this->model->$attr = '';
        $this->assertFalse($this->model->validate(array($attr)));
    }

    /*
     * unique attribute
     */
    public function AttrIsUnique($attr)
    {
        $this->model = ($this->model!== User::className())? new User():$this->model;
        $this->model->$attr['attr'] = $attr['value'];
        $this->assertFalse($this->model->validate(array($attr['attr'])));
    }

    /*
     * length attribute
     */
    public function AttrIsLength($attr, $lng)
    {
        $this->model = ($this->model!== User::className())? new User():$this->model;

        $this->model->$attr = $this->generateString($lng);
        $this->assertTrue($this->model->validate(array($attr)));

        $this->model->$attr = $this->generateString($lng+1);
        $this->assertFalse($this->model->validate(array($attr)));
    }

    function generateString($length)
    {
        $random= "";
        srand((double)microtime()*1000000);
        $char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $char_list .= "abcdefghijklmnopqrstuvwxyz";
        $char_list .= "1234567890";
        // Add the special characters to $char_list if needed

        for($i = 0; $i < $length; $i++)
        {
            $random .= substr($char_list,(rand()%(strlen($char_list))), 1);
        }
        return $random;
    }
}

<?php

namespace app\tests\unit\fixtures;


use yii\codeception\DbTestCase;
use app\tests\unit\fixtures\UserFixture;

class UserTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }

    public function testApprove()
    {
        $this->assertTrue(false);
    }
}

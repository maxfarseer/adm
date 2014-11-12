<?php

namespace app\helpers;

use yii\base\ExitException;
//use Yii;

class ExeptionJSON extends ExitException{

    const STATUS_OK = 200;
    const NO_ACCESS = 403;
    const STATUS_ERROR = 0;
    const STATUS_BAD = 0;

    public function __construct($message = "", $code = 0) {
        $this->message = $message;
        $this->code = $code;
        print $this->GenerateAnswer($message, $code);
    }

    public function GenerateAnswer($message, $code){

        $answer['data'] = $message;
        $answer['status'] = $code;

        return json_encode($answer,JSON_UNESCAPED_UNICODE);
    }
}
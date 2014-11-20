<?php

namespace app\modules\user\models;

use app\modules\present\models\Present;
use yii\base\Model;
use Yii;
use app\helpers\ExeptionJSON;

/**
 * This is the model class for table "adm_users".
 *
 * @property integer $id
 * @property string $email
 * @property string $f_name
 * @property string $s_name
 * @property string $pass
 * @property string $role
 * @property integer $status
 */

class DataFormat extends Model {


    //вынести в общее
    public static function reqRevision($req)
    {
        if(Yii::$app->request->method != $req)
            throw new ExeptionJSON('Only '.$req, ExeptionJSON::STATUS_BAD);
    }

    /**
     * encode userinfo
     * @param $answer
     * @return mixed
     */
    public static function UserInfoFormat($answer){

        $real_present = Yii::$app->params['presentAttr']['pkg'];
        $virtual_present = ['nickname', 'email'];

        $real_client = Yii::$app->params['presentAttr']['pkg'];
        $virtual_client = Yii::$app->params['presentAttr']['digit'];

        $rez['real_present'] = array_intersect_key($answer,array_flip($real_present));
        $rez['real_present']['status'] = Present::statusPresent($answer['status_pkg']);

        $rez['virtual_present'] = array_intersect_key($answer,array_flip($virtual_present));
        $rez['virtual_present']['status'] = Present::statusPresent($answer['status_digit']);

        $rez['real_client'] = (is_array($answer['pkg']))? array_intersect_key($answer['pkg'],array_flip($real_client)) : null;
        $rez['virtual_client'] = (is_array($answer['digit']))? array_intersect_key($answer['digit'],array_flip($virtual_client)) : null;

        return $rez;
    }

    /**
     * encode userinfo
     * @param $answer
     * @return mixed
     */
    public static function UserAdmFormat($users){

        $real_client = Yii::$app->params['presentAttr']['pkg'];
        $virtual_client = Yii::$app->params['presentAttr']['digit'];
        $info_client = ['id','email','f_name','s_name','address','nickname','date_reg'];

        $rezult = [];

        foreach($users as $answer) {



//        $rez['info'] = array_intersect_key($answer,array_flip($real_present));
//
//        $rez['real_present'] = array_intersect_key($answer,array_flip($real_present));
//        $rez['real_present']['status'] = Present::statusPresent($answer['status_pkg']);
//
//        $rez['virtual_present'] = array_intersect_key($answer,array_flip($virtual_present));
//        $rez['virtual_present']['status'] = Present::statusPresent($answer['status_digit']);

            $rez['info'] = array_intersect_key($answer,array_flip($info_client));
            $rez['info']['status_real'] = Present::statusPresent($answer['status_pkg']);
            $rez['info']['status_virtual'] = Present::statusPresent($answer['status_digit']);
            $rez['info']['status'] = User::statusUser($answer['status']);

            $rez['real_client'] = (is_array($answer['pkg']))? array_intersect_key($answer['pkg'],array_flip($real_client)) : null;
            $rez['virtual_client'] = (is_array($answer['digit']))? array_intersect_key($answer['digit'],array_flip($virtual_client)) : null;

            $rezult[] = $rez;
        }
        return $rezult;
    }
    /**
     * decode userinfo
     * @param $attr
     * @return array|mixed
     */
    public static function parseUserInfoFormat($attr){

        $attr = json_decode($attr,true);
        $status_digit = $attr['virtual_present']['status'];
        $status_pkg = $attr['real_present']['status'];

        $attr = array_merge($attr['real_present'], $attr['virtual_present']);

        return $attr;
    }
}

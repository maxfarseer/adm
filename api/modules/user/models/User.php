<?php

namespace app\modules\user\models;

use app\modules\present\models\Present;
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

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

//    public $id;
    public $username;
    public $pass2;
    public $authKey;
    public $accessToken;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pass','email'], 'filter', 'filter' => 'trim'],
            [['id_pkg', 'id_digit','good'], 'integer'],
            [['status_pkg', 'status_digit'], 'double'],
            [['pass','email','status','role'], 'required'],
//            [['pass2'], 'required', 'on'=>'signup'],
//            ['pass2', 'compare', 'compareAttribute' => 'pass', 'message' => 'Пароли не совпадают'],
            ['email', 'unique', 'message' => 'e-mail уже зарегистрирован'],
            [['email'], 'string', 'max' => 100],
            [['pass'], 'string', 'max' => 70],
            [['f_name','s_name','nickname'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 300],
            [['role','ref'], 'string', 'max' => 10]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['signup'] = ['pass','email'];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'email' => 'email',
            'pass' => 'Пароль',
            'role' => 'Role',
            'status' => 'Status',
            'username' => 'Логин',
            'ref' => 'ref',
            'date_reg' => 'Логин',
            'date_login' => 'Логин',
            'f_name' => 'Имя',
            's_name' => 'Фамилия',
            'nickname' => 'Никнейм',
            'address' => 'Адрес',
            'id_pkg' => 'кого поздравить посылкой',
            'status_pkg' => 'состояние поздравления посылкой',
            'id_digit' => 'кого поздравить через сайт',
            'status_digit' => 'состояние поздравления через сайт',
            'good' => 'молодцом',
        ];
    }

    public function getPkg()
    {
        return $this->hasOne(User::className(), ['id' => 'id_pkg'])
            ->from(['pkg' => User::tableName()])->select(['pkg.address', 'pkg.s_name']);
    }

    public function getDigit()
    {
        return $this->hasOne(User::className(), ['id' => 'id_digit'])
            ->from(['digit' => User::tableName()]);
    }

    /**
     * @var array EAuth attributes
     */
    public $profile;

    public static function findIdentity($id) {
        if (Yii::$app->getSession()->has('user-'.$id)) {
            return new self(Yii::$app->getSession()->get('user-'.$id));
        }
        else {
            return static::findOne(['id' => $id]);
//            return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
        }
    }

     /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$user as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::find()->where('email = "'.$username.'" and status > 0')->one();
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->pass);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    public function generatePassword($password)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /////????
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);

            // the following three lines were added:
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('author');
            $auth->assign($authorRole, $user->getId());

            return $user;
        }

        return null;
    }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */
    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $id = $service->getServiceName().'-'.$service->getId();
        $attributes = array(
            'id' => $id,
            'username' => $service->getAttribute('name'),
            'authKey' => md5($id),
            'profile' => $service->getAttributes(),
        );
        $attributes['profile']['service'] = $service->getServiceName();
        Yii::$app->getSession()->set('user-'.$id, $attributes);
        return new self($attributes);
    }

    /*
     * Information user
     */
    public static function getInfo()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        $tbl = User::tableName();
        $answer =  User::find()
            ->select([
                $tbl.'.id',$tbl.'.id_pkg',$tbl.'.id_digit',
                $tbl.'.email', $tbl.'.f_name', $tbl.'.s_name',
                $tbl.'.address', $tbl.'.nickname',
                $tbl.'.status_pkg', $tbl.'.status_digit'
            ])
            ->where([$tbl.'.id'=>Yii::$app->user->id])

            ->joinWith(['pkg'=> function ($query) {
                $query->select(['s_name','f_name','address']);
            }])

            ->joinWith(['digit'=> function ($query) {
                $query->select(['nickname']);
            }])
            ->asArray()
            ->one();

        if(!$answer)
            throw new ExeptionJSON('Ошибка получения данных', ExeptionJSON::STATUS_ERROR);

        $real_present = ['f_name','s_name', 'address'];
        $virtual_present = ['nickname', 'email'];

        $real_client = ['f_name','s_name', 'address'];
        $virtual_client = ['nickname'];

        $rez['real_present'] = array_intersect_key($answer,array_flip($real_present));
        $rez['real_present']['status'] = User::statusPresent($answer['status_pkg']);

        $rez['virtual_present'] = array_intersect_key($answer,array_flip($virtual_present));
        $rez['virtual_present']['status'] = User::statusPresent($answer['status_digit']);

        $rez['real_client'] = (is_array($answer['pkg']))? array_intersect_key($answer['pkg'],array_flip($real_client)) : null;
        $rez['virtual_client'] = (is_array($answer['digit']))? array_intersect_key($answer['digit'],array_flip($virtual_client)) : null;

        return $rez;
    }

    /*
     * Update usr info
     */
    public static function uptInfo($attr)
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

//        print_r($attr['user']);
        $attr = json_decode($attr['user'],true);
        $status_digit = $attr['virtual_present']['status'];
        $status_pkg = $attr['real_present']['status'];

        $attr = array_merge($attr['real_present'], $attr['virtual_present']);

        $attrib = User::checkAttrUpdate();

        if(sizeof($attrib) == 0)
            throw new ExeptionJSON('Нельзя обновлять информацию в текущем статусе', ExeptionJSON::STATUS_ERROR);

        $attr = array_intersect_key($attr,array_flip($attrib));

        $answer =  User::updateAll($attr,['id'=>Yii::$app->user->id]);

        if($answer === false)
            throw new ExeptionJSON('Ошибка обработки данных.', ExeptionJSON::STATUS_ERROR);

        if($answer == 0)
            throw new ExeptionJSON('Нет новых данных для обновления', ExeptionJSON::STATUS_ERROR);

        User::userChangeStatus();
        return $answer;
    }

    /*
     * registration user
     */
    public static function signUsr($attr)
    {
        if(!Yii::$app->user->isGuest)
            throw new ExeptionJSON('Уже авторизован!', ExeptionJSON::NO_ACCESS);

        $model = new User;
        $model->email = $attr['email'];
        $model->pass = $attr['pass'];

        $model->setScenario('signup');

        if (!$model->validate()) {
            $errors = json_encode($model->getErrors(), JSON_FORCE_OBJECT);
            throw new ExeptionJSON($errors, ExeptionJSON::STATUS_BAD);
        }

        $model->pass = $model->generatePassword($model->pass);
        $model->role = 'user';

        if(!$model->save(false))
            throw new ExeptionJSON('Ошибка записи данных', ExeptionJSON::STATUS_BAD);

        $auth = Yii::$app->authManager;
        $adminRole = $auth->getRole('user');
        $auth->assign($adminRole, $model->getId());

        return true;
    }

    /*
     * logout user
     */
    public static function logoutUsr()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Not login', ExeptionJSON::STATUS_BAD);

        if(!Yii::$app->user->logout())
            throw new ExeptionJSON('Logout false', ExeptionJSON::STATUS_BAD);
    }

    //вынести в общее
    public static function reqRevision($req)
    {
         if(Yii::$app->request->method != $req)
             throw new ExeptionJSON('Only '.$req, ExeptionJSON::STATUS_BAD);
    }

    /**
     * get digit user
     */
    public static function usrDigit()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        if(!User::checkAccessPresent('digit'))
            throw new ExeptionJSON('Не заполнены необходимые поля анкеты', ExeptionJSON::STATUS_ERROR);

        if(Yii::$app->user->identity->id_digit != 0)
            throw new ExeptionJSON('Поздравитель уже получен', ExeptionJSON::STATUS_ERROR);

        $model = User::find()->select(['id','email','f_name'])->where(['AND',['status_digit'=>'1'],['id_digit' => '0']])->orderBy('RAND()')->one();

        if(!$model)
            throw new ExeptionJSON('На данный момент нет претендентов на получение подарка', ExeptionJSON::STATUS_ERROR);

        $present = new Present();
        $present -> from = Yii::$app->user->id;
        $present -> to = $model->id;
        $present -> type = 'digit';
        $present -> status = 0;
        $present -> date = date('Y-m-d H:i:s');

        if($present -> save())
            User::updateAll(['id_digit' => $model->id],['id' => Yii::$app->user->id]);

        return $model->nickname;
    }

    /**
     * get pkg user
     */
    public static function usrPkg()
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        if(!User::checkAccessPresent('pkg'))
            throw new ExeptionJSON('Не заполнены необходимые поля анкеты', ExeptionJSON::STATUS_ERROR);

        if(Yii::$app->user->identity->id_pkg != 0)
            throw new ExeptionJSON('Поздравитель уже получен', ExeptionJSON::STATUS_ERROR);

        $model = User::find()->select(['id','s_name','f_name','address'])->where(['AND',['>=','status_pkg','2'],['id_digit' => '0']])->orderBy('RAND()')->one();

        if(!$model)
            throw new ExeptionJSON('На данный момент нет претендентов на получение подарка', ExeptionJSON::STATUS_ERROR);

        $present = new Present();
        $present -> from = Yii::$app->user->id;
        $present -> to = $model->id;
        $present -> type = 'pkg';
        $present -> status = 0;
        $present -> date = date('Y-m-d H:i:s');

        if($present -> save())
            User::updateAll(['id_pkg' => $model->id],['id' => Yii::$app->user->id]);

        return [$model->f_name, $model->s_name, $model->address];
    }

    public static function checkAttrUpdate(){

        $attr = [];
        if(Yii::$app->user->identity->status_pkg != 2)
            $attr = array_merge($attr,['s_name','address','f_name']);

        if(Yii::$app->user->identity->status_digit != 2)
            $attr = array_merge($attr,['email','nickname']);

        $attr = array_unique($attr);

        return $attr;
    }

    public static function checkAccessPresent($present){

        switch($present){
            case('digit'):
                $attr = ['email','nickname'];
                break;
            case('pkg'):
                $attr = ['s_name','address','f_name'];
                break;
        }

        foreach($attr as $v)
            if(empty(Yii::$app->user->identity->$v)) return false;

        return true;
    }

    public static function statusPresent($status){

        $stat = [
            '0' => 'free',
            '1' => 'verifying',
            '2' => 'blocked',
        ];

        return $stat[$status];
    }

    public static function userChangeStatus(){

        $attr = [];
        if(User::checkAccessPresent('digit')) $attr = array_merge($attr,['status_digit' => 1]);
        if(User::checkAccessPresent('pkg')) $attr = array_merge($attr,['status_pkg' => 1]);

        if(sizeof($attr)>0)
            return User::updateAll($attr,['id'=>Yii::$app->user->id]);

        return false;
    }
}

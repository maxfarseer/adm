<?php

namespace app\modules\user\models;

use app\modules\present\models\Present;
use app\modules\user\models\DataFormat;
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

    public function getPresent()
    {
        return $this->hasOne(Present::className(), ['from' => 'id'])
            ->from(['present' => Present::tableName()]);
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

    public static function statusUser($status) {

        $stat = [
            '0' => 'BAN',
            '1' => 'ACTIVE',
        ];

        return $stat[$status];
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
                $query->select(Yii::$app->params['presentAttr']['pkg']);
            }])

            ->joinWith(['digit'=> function ($query) {
                $query->select(Yii::$app->params['presentAttr']['digit']);
            }])
            ->asArray()
            ->one();

        if(!$answer)
            throw new ExeptionJSON('Ошибка получения данных', ExeptionJSON::STATUS_ERROR);

        $rez = DataFormat::UserInfoFormat($answer);

        return $rez;
    }

    /*
     * Update usr info
     */
    public static function uptInfo($attr)
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        //список атрибутов на изменение
        $attr = DataFormat::parseUserInfoFormat($attr);

        //список атрибутов доступных для изменения
        $attrib = User::checkAttrUpdate();

        if(sizeof($attrib) == 0)
            throw new ExeptionJSON('Нельзя обновлять информацию в текущем статусе', ExeptionJSON::STATUS_ERROR);

        $attr = array_intersect_key($attr,array_flip($attrib));

        $answer =  User::updateAll($attr,['id'=>Yii::$app->user->id]);

        if($answer === false)
            throw new ExeptionJSON('Ошибка обработки данных.', ExeptionJSON::STATUS_ERROR);

        if($answer == 0)
            throw new ExeptionJSON('Нет новых данных для обновления', ExeptionJSON::STATUS_ERROR);

        // установить статус пользователя исходя из заполненности анкеты
        User::userChangeStatus();
        return $answer;
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

    public static function userChangeStatus(){

        $attr = [];
        if(User::checkAccessPresent('digit')) $attr = array_merge($attr,['status_digit' => 1]);
        if(User::checkAccessPresent('pkg')) $attr = array_merge($attr,['status_pkg' => 1]);

        if(sizeof($attr)>0)
            return User::updateAll($attr,['id'=>Yii::$app->user->id]);

        return false;
    }

    public static function userBan($id,$status = 0){

        if(Yii::$app->user->can('moderator'))
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        return User::updateAll(['status' => $status],['id' => $id]);
    }

    /*
    * Information user
    */
    public static function getUsr($id = false)
    {
        if(Yii::$app->user->can('moderator'))
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        $tbl = User::tableName();

        $where = (!$id)?[$tbl.'.id_digit'=>0]:[$tbl.'.id'=>$id];

        $answer =  User::find()
            ->select([
                $tbl.'.id',$tbl.'.id_pkg',$tbl.'.id_digit',
                $tbl.'.email', $tbl.'.f_name', $tbl.'.s_name',
                $tbl.'.address', $tbl.'.nickname',
                $tbl.'.status_pkg', $tbl.'.status_digit',
                $tbl.'.date_reg', $tbl.'.status'
            ])
            ->where($where)

            ->joinWith(['pkg'=> function ($query) {
                $query->select(Yii::$app->params['presentAttr']['pkg']);
            }])

            ->joinWith(['digit'=> function ($query) {
                $query->select(Yii::$app->params['presentAttr']['digit']);
            }])

            ->joinWith(['present'=> function ($query) {
                $query->select(['message','comment']);
            }])

            ->asArray()
            ->all();

        if(!$answer)
            throw new ExeptionJSON('Ошибка получения данных', ExeptionJSON::STATUS_ERROR);

        $rez = DataFormat::UserAdmFormat($answer);

        return $rez;
    }
}

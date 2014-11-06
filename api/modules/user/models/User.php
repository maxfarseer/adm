<?php

namespace app\modules\user\models;

use Yii;
use app\helpers\ExeptionJSON;

/**
 * This is the model class for table "adm_users".
 *
 * @property integer $id
 * @property string $email
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
            [['status', 'id_fester', 'good'], 'integer'],
            [['pass','email','status','role'], 'required'],
//            [['pass2'], 'required', 'on'=>'signup'],
//            ['pass2', 'compare', 'compareAttribute' => 'pass', 'message' => 'Пароли не совпадают'],
            ['email', 'unique', 'message' => 'e-mail уже зарегистрирован'],
            [['email'], 'string', 'max' => 20],
            [['pass'], 'string', 'max' => 70],
            [['f_name','s_name'], 'string', 'max' => 50],
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
            'address' => 'Адрес',
            'id_fester' => 'кого поздравить',
            'good' => 'молодцом',
        ];
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

        $answer =  User::find()
            ->select(['email','f_name','s_name','address'])
            ->where(['id'=>Yii::$app->user->id])
            ->asArray()
            ->one();

        if(!$answer)
            throw new ExeptionJSON('Ошибка получения данных', ExeptionJSON::STATUS_ERROR);

        return $answer;
    }

    /*
     * Update usr info
     */
    public static function uptInfo($attr)
    {
        if(Yii::$app->user->isGuest)
            throw new ExeptionJSON('Авторизуйтесь!', ExeptionJSON::NO_ACCESS);

        $attr = array_intersect_key($attr,array_flip(['f_name','s_name','address']));

        $answer =  User::updateAll($attr,['id'=>Yii::$app->user->id]);
        if(!$answer)
        throw new ExeptionJSON('Ошибка обработки данных.', ExeptionJSON::STATUS_ERROR);
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
}

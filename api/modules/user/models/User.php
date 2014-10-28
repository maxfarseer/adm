<?php

namespace app\modules\user\models;

use Yii;


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

    /*
     * Info user
     */
    public static function getInfo()
    {
        $answer =  User::find()
            ->select(['email','f_name','s_name','address'])
            ->where(['id'=>Yii::$app->user->id])
            ->asArray()
            ->one();

        return $answer;
    }

    /*
     * Update usr info
     */
    public static function uptInfo($attr)
    {
        $attr = array_intersect_key($attr,array_flip(['email','f_name','s_name','address']));
        $answer =  User::updateAll($attr,['id'=>Yii::$app->user->id]);

        return $answer;
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
}

<?php

namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'   => 'username or email',
            'password'   => 'password',
            'rememberMe' => 'Remember me',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $duration = $this->rememberMe ? 30 * 24 * 3600 : 0;
            return Yii::$app->user->login($this->getUser(), $duration);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            // Tafuta kwa username kwanza
            $this->_user = User::findByUsername($this->username);

            // Kama hajapatikana — tafuta kwa email
            if ($this->_user === null) {
                $this->_user = User::findByEmail($this->username);
            }
        }
        return $this->_user;
    }
}
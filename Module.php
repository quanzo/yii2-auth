<?php

namespace x51\yii2\modules\auth;
use \yii\helpers\Url;

/**
 * auth module definition class
 */
class Module extends \yii\base\Module
{
    public $signupUrl = '';

    /*public function behaviors() {
    return [
    'access' => [
    'class' => AccessControl::className(),
    'rules' => [
    [
    'controllers' => [$this->id.'/profile'],
    'actions' => ['signup'],
    'allow' => $this->allowSignup,
    'roles' => ['?'],
    ],
    ],
    ],
    ];
    }*/

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'x51\yii2\modules\auth\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (!isset($this->module->i18n->translations['module/auth'])) {
            $this->module->i18n->translations['module/auth'] = [
                'class' => '\yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'module/blocks' => 'messages.php',
                ],
            ];
        }
    }

    public function getStatus() {
        $res = [
            'logged' => false,
            'login' => '',
            'url' => [
                'auth' => Url::to(['/'.$this->id.'/default/login']),
                'signup' => $this->signupUrl,
                'logout' => ''
            ]
        ];
        $user = \Yii::$app->user;
        if (!$user->isGuest) {
            $curUserModel = $user->getIdentity();
            $res['logged'] = true;
            $res['login'] = $curUserModel->username;
            $res['url']['auth'] = '';
            $res['url']['signup'] = '';
            $res['url']['logout'] = Url::to(['/'.$this->id.'/default/logout']);            
        }
        return $res;
    } // end getStatus

}

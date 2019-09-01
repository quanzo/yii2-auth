<?php
namespace x51\yii2\modules\auth\controllers;

use \x51\yii2\modules\auth\models\LoginForm;
use \x51\yii2\modules\auth\models\PasswordResetRequestForm;
use \x51\yii2\modules\auth\models\ResetPasswordForm;
use \yii\filters\AccessControl;
use \yii\filters\VerbFilter;
use \Yii;

class DefaultController extends \yii\web\Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'request-password-reset'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                ],
            ],*/
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            $this->redirect(['default/login']);
        } else {
            return $this->render('success', []);
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            //return $this->goHome();
            //return $this->render('success', []);
            \Yii::$app->session->setFlash('success', Yii::t('module/auth', 'You are logged in'));
            return $this->goHome();
        }

        $error = '';
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->login()) {
                //return $this->render('success', []);
                \Yii::$app->session->setFlash('success', Yii::t('module/auth', 'You are logged in'));
                return $this->goHome();
            } else {
                $error = \Yii::t('module/auth', 'Incorrect login or password.');
            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'error' => $error,
        ]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                \Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            \Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    } // end actionResetPassword

    /**
     * Роли текущего пользователя
     *
     * @return void
     */
    public function actionRole() {
        if (\Yii::$app->user->isGuest) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('module/auth', 'You are not authorized'));
        } else {
            $user = \Yii::$app->user;
            $params = [
                'user' => [
                    'id' => $user->id,
                    'user' => $user->getIdentity(),
                    'rolerule' => Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)
                ]
            ];
            return $this->render('rolerule', $params);
        }      
    } // end actionRole



}

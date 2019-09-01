<?php
/* Ошибка авторизации */
use \Yii;

$this->title = Yii::t('module/auth', 'Error');
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="error"><?php
if (!empty($message)) {
    echo $message;
} else {
    echo Yii::t('module/auth', 'Incorrect login or password.');
}?></div>
</div>

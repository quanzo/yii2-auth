<?php
/* Авторизован */
use \yii\helpers\Html;

$this->title = Yii::t('module/auth', 'Authorized');
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="success"><?php
if (!empty($message)) {
    echo $message;
} else {
    echo Yii::t('module/auth', 'You are logged in.');
}?></div>

</div>
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="title"><?=Html::encode($this->title)?></h1>
<?php
if (!empty($error)) {
    echo '<div class="error">' . $error . '</div>';
}?>
<div class="info">Please fill out your email. A link to reset password will be sent there.</div>
<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']);?>
<?php
$af = $form->field($model, 'email');
$af->template = "<div style=\"position:relative\">{input}{label}<div class=\"bar\"></div></div>{hint}{error}";
echo $af->textInput(['autofocus' => true, 'required' => 'required']);
?>
<div class="button-container">
    <?=Html::submitButton('Reset', ['class' => 'btn btn-primary', 'name' => 'reset-password'])?>
</div>
<?php /*<div class="footer">&nbsp;</div>*/ ?>
<?php ActiveForm::end(); ?>

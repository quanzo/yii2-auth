<?php
use \Yii;
use \yii\rbac\Item;

$this->title = Yii::t('module/auth', 'Current user role and rule');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('module/auth', 'Auth'),
    'url' => ['/' . $this->context->module->id],
];
$this->params['breadcrumbs'][] = Yii::t('module/auth', 'Role and rule');
?>
<h1 class="title"><?=Yii::t('module/auth', 'User role and rule')?></h1>
<div class="form-group">
<?php
foreach ($user['rolerule'] as $id => $role) {
    echo '<h2 class="role-id">'.$id.'</h2>';
    
    $type = 'permission';
    switch ($role->type) {
        case Item::TYPE_ROLE: {
            $type = 'role';
            break;
        }
        case Item::TYPE_PERMISSION: {
            $type = 'permission';
            break;
        }
    }
    echo '<div class="role-type">' . Yii::t('module/auth', 'Type: {type}', ['type' => $type]) . '</div>';
    echo '<div class="role-name">'.Yii::t('module/auth', 'Name: {name}',['name' => $role->name]).'</div>';
    echo '<div class="role-description">' . Yii::t('module/auth', 'Description: {desc}',['desc' => $role->description]) . '</div>';
}
?>
</div>



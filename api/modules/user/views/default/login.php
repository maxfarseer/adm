<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LoaderFH;

$this->title = 'Авторизация';
?>


<?php
if (Yii::$app->getSession()->hasFlash('error')) {
    echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
}
//echo Yii::$app->getSecurity()->generatePasswordHash('123');
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Авторизация', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

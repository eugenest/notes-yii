<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Send note by e-mail';
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('noteSended')): ?>
        <div class="alert alert-success">
            Note successfully sended.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'note-mail-form']) ?>
                    <?= $form->field($email, 'email') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
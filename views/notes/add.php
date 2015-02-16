<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Add note';
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('noteAdded')): ?>
        <div class="alert alert-success">
            Form successfully added.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'note-form']); ?>
                    <?= $form->field($note, 'title') ?>
                    <?= $form->field($note, 'author') ?>
                    <?= $form->field($note, 'description')->textArea(['rows' => 4]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
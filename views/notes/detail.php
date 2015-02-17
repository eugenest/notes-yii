<?php
use yii\helpers\Url;
?>

<div class="site-index">
    <h1>
        Note: <?= $note->title; ?>
        <div class="notes-controls">
            <a href="<?= Url::toRoute(['edit', 'id' => $note->id]); ?>">
                <button class="btn btn-primary btn-xs">Edit</button>
            </a>
            <a href="<?= Url::toRoute(['delete', 'id' => $note->id]); ?>">
                <button class="btn btn-danger btn-xs">&times;</button>
            </a>
            <a href="<?= Url::toRoute(['csv', 'id' => $note->id]); ?>" target="_blank">
                <button class="btn btn-primary btn-xs">CSV</button>
            </a>
            <a href="<?= Url::toRoute(['send-mail', 'id' => $note->id]); ?>">
                <button class="btn btn-primary btn-xs">Mail</button>
            </a>
        </div>
    </h1>
    <p><?= $note->create_date; ?> : <?= $note->author; ?></p>
    <p><?= $note->description; ?></p>
    <?php if ($note->image): ?>
        <img src="<?= $note->image ?>" />
    <?php else :?>
        <div class="alert alert-info">
            There is no image for this note. 
        </div>
    <?php endif ?>
</div>

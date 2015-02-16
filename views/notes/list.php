<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="site-index">
    
    <h1>Notes</h1>
    
    <p>
        <a href="<?= Url::toRoute(['add']); ?>">
            <button class="btn btn-primary">Add</button>
        </a>
    </p>
    <?php if (count($notes) > 0) : ?>
        <ul>
        <?php foreach ($notes as $note): ?>
            <li>
                <a href="<?= Url::toRoute(['detail', 'id' => $note->id]); ?>">
                    <?= Html::encode("{$note->title} ({$note->description})") ?>
                </a>
                <a href="<?= Url::toRoute(['edit', 'id' => $note->id]); ?>">
                    <button class="btn btn-primary btn-xs">Edit</button>
                </a>
                <a href="<?= Url::toRoute(['delete', 'id' => $note->id]); ?>">
                    <button class="btn btn-danger btn-xs">&times;</button>
                </a>
                <button class="btn btn-primary btn-xs">CSV</button>
                <button class="btn btn-primary btn-xs">Mail</button>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
        <div class="alert alert-info">
            There is no notes.
        </div>
    <?php endif;?>
</div>

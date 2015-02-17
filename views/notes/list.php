<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<div class="site-index">
    
    <h1>Notes</h1>
    
    <p>
        <a href="<?= Url::toRoute(['add']); ?>">
            <button class="btn btn-primary">Add</button>
        </a>
    </p>
    <?php if (count($notes) > 0) : ?>
        <ul class="notes-list">
        <?php foreach ($notes as $note): ?>
            <li>
                <a href="<?= Url::toRoute(['detail', 'id' => $note->id]); ?>">
                    <?= Html::encode("{$note->title} ({$note->author})") ?>
                </a>
                <?php if ($note->image): ?>
                    <span title="That note contains image">*</span>
                <?php endif ?>
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
                    <a href="<?= Url::toRoute(['mail', 'id' => $note->id]); ?>" target="_blank">
                        <button class="btn btn-primary btn-xs">Mail</button>
                    </a>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        
        <?php echo LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    <?php else :?>
        <div class="alert alert-info">
            There is no notes.
        </div>
    <?php endif;?>
</div>

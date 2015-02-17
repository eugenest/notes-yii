<?php

namespace app\models;

use yii\db\ActiveRecord;

class Note extends ActiveRecord
{
    public static function tableName() {
        return 'notes';
    }
}

<?php

namespace app\models;

use yii\db\ActiveRecord;

class Note extends ActiveRecord
{
    /*public $id;
    public $title;
    public $description;
    public $create_date;
    public $image;
    public $author;*/
    
    public static function tableName() {
        return 'notes';
    }
}

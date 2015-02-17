<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Note;
use app\helpers\Notes as NotesHelper;

class NoteController extends ActiveController
{
    public $modelClass = 'app\models\Note';
    
    public function actionCsv()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::find()->where(['id' => $id])->asArray()->one();
        
        if (!empty($note['image'])) {
            $note['image'] = NotesHelper::getEncodedImage($note['image']);
        }
        
        NotesHelper::downloadSendHeaders("data_export_" . date("Y-m-d") . ".csv");
        echo NotesHelper::array2csv(array($note));
    }
    
    public function actionMail()
    {
        $_POST = json_decode(file_get_contents('php://input'), true); //TODO find clearer way
        
        if (!empty(Yii::$app->request->post())) {
            $request = Yii::$app->request->post();
            
            $id = $request['id'];
            $note = Note::find()->where(['id' => $id])->asArray()->one();
            
            if (!empty($note['image'])) {
                $note['image'] = NotesHelper::getEncodedImage($note['image']);
            }
            
            $message = \Yii::$app->mailer->compose();
            $message
            ->setFrom('notes@yii.com')
            ->setTo($request['email'])
            ->setSubject($note['title'])
            ->setTextBody($note['description']);
            
            $attachContent = NotesHelper::array2csv(array($note));
            $message->attachContent($attachContent, ['fileName' => 'attach.csv', 'contentType' => 'text/csv']);
    
            if ($message->send()) {
                \Yii::$app->getResponse()->setStatusCode(200);
            }
        }
    }
}
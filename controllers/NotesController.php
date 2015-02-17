<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Note;
use app\models\UploadImage;
use app\models\SendMailForm;
use yii\web\UploadedFile;
use app\helpers\Notes as NotesHelper;
use yii\data\Pagination;

class NotesController extends Controller
{
    public function actionIndex()
    {
        $notes = Note::find();
        
        $countQuery = clone $notes;
        
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        
        $notes = $notes
                ->orderBy(array('create_date' => SORT_DESC))
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        
        return $this->render('list', array('notes' => $notes, 'pages' => $pages));
    }
    
    public function actionDetail()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::findOne($id);
        return $this->render('detail', array('note' => $note));
    }
    
    public function actionEdit()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::findOne($id);
        
        $image = new UploadImage();
        $image->file = ($note->image) ? $note : '';
        
        if (!empty(Yii::$app->request->post())) {
            $editedNote = Yii::$app->request->post();
            
            foreach ($editedNote['Note'] as $fieldKey => $fieldValue) {
                $note->{$fieldKey} = $fieldValue;
            }
            
            $image->file = UploadedFile::getInstance($image, 'file');
            
            if ($image->file) {
                $note->image = NotesHelper::saveImage($image);
            }
            
            if ($note->save()) {
                Yii::$app->session->setFlash('noteEdited');
            }
        }
        return $this->render('edit', array('note' => $note, 'image' => $image));
    }
    
    public function actionAdd()
    {
        $note = new Note();
        $image = new UploadImage();

        if (!empty(Yii::$app->request->post())) {
            $addedNote = Yii::$app->request->post();
            
            foreach ($addedNote['Note'] as $fieldKey => $fieldValue) {
                $note->{$fieldKey} = $fieldValue;
            }
            
            $image->file = UploadedFile::getInstance($image, 'file');
            
            if ($image->file) {
                $note->image = NotesHelper::saveImage($image);
            }
            
            if ($note->save()) {
                Yii::$app->session->setFlash('noteAdded');
            }
        }
        return $this->render('add', array('note' => $note, 'image' => $image));
    }
    
    public function actionDelete()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::findOne($id);
        $note->delete();
        
        return $this->redirect('index', 302);
    }
    
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
    
    public function actionSendMail()
    {
        if (!empty(Yii::$app->request->post())) {
            $email = Yii::$app->request->post();
            
            $id = Yii::$app->getRequest()->getQueryParam('id');
            $note = Note::find()->where(['id' => $id])->asArray()->one();
            
            if (!empty($note['image'])) {
                $note['image'] = NotesHelper::getEncodedImage($note['image']);
            }
            
            $message = \Yii::$app->mailer->compose();
            $message
            ->setFrom('notes@yii.com')
            ->setTo($email['SendMailForm']['email'])
            ->setSubject($note['title'])
            ->setTextBody($note['description']);
            
            $attachContent = NotesHelper::array2csv(array($note));
            $message->attachContent($attachContent, ['fileName' => 'attach.csv', 'contentType' => 'text/csv']);
    
            if ($message->send()) {
                Yii::$app->session->setFlash('noteSended');
            }
        }
        
        $email = new SendMailForm();
        return $this->render('mail', ['email' => $email]);
    }
}

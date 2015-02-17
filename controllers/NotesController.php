<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Note;
use app\models\UploadImage;
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
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        
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
                $uploadPath = '/upload/' . $image->file->baseName . '.' . $image->file->extension;
                $filePath = \Yii::$app->basePath . $uploadPath;
                $image->file->saveAs($filePath);
                $note->image = $uploadPath;
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
            $editedNote = Yii::$app->request->post();
            
            foreach ($editedNote['Note'] as $fieldKey => $fieldValue) {
                $note->{$fieldKey} = $fieldValue;
            }
            
            $image->file = UploadedFile::getInstance($image, 'file');
            
            if ($image->file) {
                $uploadPath = '/upload/' . $image->file->baseName . '.' . $image->file->extension;
                $filePath = \Yii::$app->basePath . $uploadPath;
                $image->file->saveAs($filePath);
                $note->image = $uploadPath;
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
            $imagePath = \Yii::$app->basePath . $note['image'];
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            $imageData = file_get_contents($imagePath);
            $note['image'] = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        }
        
        NotesHelper::downloadSendHeaders("data_export_" . date("Y-m-d") . ".csv");
        echo NotesHelper::array2csv(array($note));
    }
    
    public function actionMail()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::find()->where(['id' => $id])->asArray()->one();
        
        if (!empty($note['image'])) {
            $imagePath = \Yii::$app->basePath . $note['image'];
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            $imageData = file_get_contents($imagePath);
            $note['image'] = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        }
        
        $message = \Yii::$app->mailer->compose();
        $message
        ->setFrom('notes@yii.com')
        ->setTo('eugene.timofieiev@gmail.com')
        ->setSubject($note['title'])
        ->setTextBody($note['description']);
        
        $attachContent =  NotesHelper::array2csv(array($note));
        $message->attachContent($attachContent, ['fileName' => 'attach.csv', 'contentType' => 'text/csv']);

        $message->send();
    }
}

<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Note;

class NotesController extends Controller
{
    public function actionIndex()
    {
        $notes = Note::find()->orderBy('title')->all();
        return $this->render('list', array('notes' => $notes));
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
        
        if (!empty(Yii::$app->request->post())) {
            $editedNote = Yii::$app->request->post();
            
            foreach ($editedNote['Note'] as $fieldKey => $fieldValue) {
                $note->{$fieldKey} = $fieldValue;
            }
            
            if ($note->save()) {
                Yii::$app->session->setFlash('noteEdited');
            }
        }
        return $this->render('edit', array('note' => $note));
    }
    
    public function actionAdd()
    {
        $note = new Note();
        
        if (!empty(Yii::$app->request->post())) {
            $editedNote = Yii::$app->request->post();
            
            foreach ($editedNote['Note'] as $fieldKey => $fieldValue) {
                $note->{$fieldKey} = $fieldValue;
            }
            
            if ($note->save()) {
                Yii::$app->session->setFlash('noteAdded');
            }
        }
        
        return $this->render('add', array('note' => $note));
    }
    
    public function actionDelete()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::findOne($id);
        $note->delete();
        
        return $this->redirect('index', 302);
    }
    
    public function actionGetCSV()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $note = Note::findOne($id);
        echo json_encode($note);
    }
}

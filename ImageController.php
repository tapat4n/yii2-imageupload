<?php

namespace ssoft\imageupload;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


use ssoft\imageupload\ImageFileModel;
use yii\web\UploadedFile;

use yii\helpers\Json;

class ImageController extends Controller
{
    public $enableCsrfValidation = true;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['upload'],
                'rules' => [
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload' => ['post'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],            
        ];
    }

    public function actionUpload(){
        $result['uploaded']=false;
        $uploadModel = new ImageFileModel();
        $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');
        if($uploadModel->validate()) {
            $fileName = time();
            $fileExtension = $uploadModel->file->extension;
            $uploaded = $uploadModel->file->saveAs(Yii::getAlias('@webroot') . '/files/' .$fileName.'.'.$fileExtension);
            $result = [
                'uploaded' => $uploaded,
                'name' => $fileName,
                'extension' => $fileExtension,
            ];
        }
        echo JSON::encode($result);
    }
}

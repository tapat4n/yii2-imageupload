<?php

namespace tapat4n\imageupload;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
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
                        'allow' => true
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

    public function actionUpload()
    {
        $post = Yii::$app->request->post();
        $result['uploaded'] = false;
        $uploadModel = new ImageFileModel();
        $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');
        $uploadModel->path = $post['path'];

        if ($uploadModel->validate()) {
            $fileName = time();
            $fileExtension = $uploadModel->file->extension;
            $path = base64_decode($uploadModel->path);
            $uploaded = $uploadModel->file->saveAs($path . '/' . $fileName . '.' . $fileExtension);
            $result = [
                'uploaded' => $uploaded,
                'name' => $fileName,
                'extension' => $fileExtension,
            ];
        }
        echo JSON::encode($result);
    }
}

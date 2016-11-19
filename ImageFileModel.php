<?php

namespace ssoft\imageupload;

use Yii;
use yii\base\Model;

class ImageFileModel extends Model
{
    public $file;
    
    public function rules()
    {
        return [
            [
                'file', 
                'file', 
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
        ];
    }
}

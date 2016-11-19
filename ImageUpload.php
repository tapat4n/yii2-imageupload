<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ssoft\imageupload;

use yii\base\Widget;
use ssoft\imageupload\ImageUploadAsset;
use yii\helpers\Html;
use yii\web\View;

/**
 * @author Sergii Kozin <sergagame1@gmail.com>
 * @since 2.0
 */
class ImageUpload extends Widget
{
    
    public $model;

    public $attribute;


    //public $valueField;
    public $parametersAttibute;
    public $addClass;
    //public $uploadUrl;
    //public $downloadUrl;
    public $imageUrl;
    public $imagePath;
    public $placeholder;
    public $height = 150;
    public $width = 150;
    public $aspectRatio = 1;

    private $_inited = false;

    public function init() {
    	$view = $this->getView();
        ImageUploadAsset::register($view);
        //$this->aspectRatio = $this->width / $this->height;
        parent::init();
    }

    public function run(){ //echo print_r($this->attribute); die();"UserImageUpload",
        $aspectRatio = $this->aspectRatio != 0 ? ', \'aspectRatio\':\''.$this->aspectRatio.'\'' : '';
        $this->getView()->registerJs('
            imageUploadInit(        
            "'.$this->model->formName().'",            
            "'.$this->attribute.'",
            "'.$this->parametersAttibute.'", 
            {\'height\':\''.$this->height.'\', \'width\':\''.$this->width.'\''.$aspectRatio.'});
        ', View::POS_READY);

         $this->getView()->registerCss('
            div.dz-'.$this->attribute.' .dz-image-upload {  
                width: '.$this->width.'px; 
                height: '.$this->height.'px;
            }

            div.dz-'.$this->attribute.' .dz-button {
                background-color: rgba(255, 255, 255, 0.4);
                padding: 0 0.4em;
                border-radius: 3px;
                position: absolute;
                left: '.($this->width-30).'px;
                width: 27px;
            }
        ');

        $element='';
        //$form->field($imageModel, 'image_parameters')parametersAttibute
        //$this->parametersField->hiddenInput();
        $element.=Html::activeHiddenInput($this->model, $this->parametersAttibute);
        $element.=Html::activeHiddenInput($this->model, $this->attribute);
        //echo print_r($this->valueParamField->attribute);
        //$element.=Html::activeHiddenInput($this->model, $this->valueField);
        //$element.=Html::activeHiddenInput($this->model, $this->valueParamField);
        $element.=$this->render('@app/views/widgets/modal', [
            'id'=> $this->attribute.'-upload-modal',
            'addClass' => 'image-crop',
            'title' => 'Редактирование изображения',
            'content' => '
                <div class="img-container" style="width:100%"> 
                    <img src="" id="'.$this->attribute.'-upload-image"/>
                </div>',
            'buttons' =>'
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>'
        ]);
        $element.=sprintf('<div class="dropzone dz-default dz-message dz-%s %s" image-url="%s" image-path="%s" upload-action="%s" style="width:%dpx; height:%dpx; min-height:%dpx;">
            <div class="dz-message" data-dz-message><span class="dz-placeholder-message">%s</span></div>
        </div>', 
        $this->attribute, $this->addClass, $this->imageUrl, crypt($this->imagePath, '1111'), \yii\helpers\Url::to(['imageupload/upload']),
        $this->width, $this->height, $this->height, 
        $this->placeholder);

        $element.='<!--'.\Yii::$app->request->csrfToken.'-->';

        return $element;
    }
}
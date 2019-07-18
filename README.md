# yii2-imageupload

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist sergeykoz/yii2-imageupload
```

or add

```json
"sergeykoz/yii2-imageupload": "0.2.0",
```

to the `require` section of your composer.json.

Usage
---------------------

Add upload widget to a form
```php
<?php
    use ssoft\imageupload\ImageUpload;    
    
    echo $form = ActiveForm::begin(['id' => 'image-form', 'options' => ['enctype'=>'multipart/form-data']]);
    
    // first way
    echo ImageUpload::widget([
        'model' => $photoModel,
        'attribute' => 'photo',
        'parametersAttibute' => 'photo_parameters',
        'addClass' => 'col-sm-8',
        'imageUrl' => Yii::getAlias('@web') . '/files',
        'imagePath' => Yii::getAlias('@webroot') . '/files',
        'placeholder' => 'Photo',
        'size' => ['height' => 300, 'width' => 100],    
        'aspectRatio' => 0.33,
        'disabled' => false
    ]);
        
    // second way
    echo $form->field($photoModel, 'image')->widget(ImageUpload::className(), [
        'parametersAttibute' => 'image_parameters',
        'imageUrl' => Yii::getAlias('@web') . '/files',
        'imagePath' => Yii::getAlias('@webroot') . '/files',
        'placeholder' => 'Image'
    ]);
    
    echo ActiveForm::end();
?>
```

Configure main.php
```php
'controllerMap' => [            
    'imageupload' => 'ssoft\imageupload\ImageController',
],
```

Manage of uploaded images
```php
<?php
    use ssoft\imageupload\Image;

    // create instance of the image
    $image = new Image([
        'imagePath' => Yii::getAlias('@webroot') . '/files',
        'imageFile' => $photoModel->photo,
        'parameters' => $photoModel->photo_parameters,
    ]);
    
    // save the image with size 100x300 with name Filename100x300.Ext
    $image->save(
        Yii::getAlias('@webroot') . '/files',
        Image::thumbnailName($photoModel->photo, ['height' => 300, 'width' => 100]), 
        ['height' => 300, 'width' => 100]
    );
    
    // get content of the image png
    echo $image->show('png', ['height' => 600, 'width' => 600]);
?>
```






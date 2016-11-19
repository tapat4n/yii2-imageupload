<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ssoft\imageupload;

use yii\web\AssetBundle;

/**
 * @author Sergii Kozin <sergagame1@gmail.com>
 * @since 2.0
 */
class ImageUploadAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $sourcePath = __DIR__ . '/assets';
    public $css = [        
        'css/imageupload.css',
    ];
    public $js = [        
        'js/imageupload.js',
    ];   
    public $depends = [  
        'yii\web\YiiAsset', 
        'yii\bootstrap\BootstrapAsset',     
        'ssoft\imageupload\ImagePackagesAsset',        
    ];
}
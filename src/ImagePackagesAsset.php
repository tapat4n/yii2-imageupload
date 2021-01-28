<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace tapat4n\imageupload;

use yii\web\AssetBundle;

/**
 * @author Sergii Kozin <sergagame1@gmail.com>
 * @since 2.0
 */
class ImagePackagesAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'cropper/dist/cropper.css',
        'dropzone/dist/min/dropzone.min.css'
    ];

    public $js = [
        'cropper/dist/cropper.js',
        'dropzone/dist/min/dropzone.min.js'
    ];
}
<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ssoft\imageupload;

use yii\helpers\Json;
use \Imagine\Gd\Imagine;
use \Imagine\Image\Box;
use \Imagine\Image\Point;
use \Imagine\Image\ImageInterface;

/**
 * @author Sergii Kozin <sergagame1@gmail.com>
 * @since 2.0
 */
class Image
{
    /* @var \Imagine\Gd\Imagine $_image */
    private $_image;

    /**
     * Creates an `Image` object based on the image model parameters .
     * @param array $options for construction the object
     */
    public function __construct($options)
    {
        $imagePath = $options['imagePath'];
        $imageFile = $options['imageFile'];
        $parameters = Json::decode($options['parameters']);
        $this->_image = (new Imagine())->open($imagePath . '/' . $imageFile);
        $this->_image->crop(new Point($parameters['data']['x'], $parameters['data']['y']), new Box($parameters['data']['width'], $parameters['data']['height']));
    }

    /**
     * Saves cropped file with name and size
     * @param string $path path to folder
     * @param string $file file name
     * @param array $size size parameters ['width' => xxx, 'height' => yyy]
     * @param array $options Imagine parameters
     * @return $this return itself
     */
    public function save($path, $file, $size, $options = [])
    {
        $this->_image
            ->thumbnail(new Box($size['width'], $size['height']), ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($path . '/' . $file, $options);
        return $this;
    }

    /**
     * Gets content of the image
     * @param string $type type of image jpg, png
     * @param array $size size parameters ['width' => xxx, 'height' => yyy]
     * @return string content of the image
     */
    public function show($type, $size)
    {
        return $this->_image->show($type)
            ->thumbnail(new Box($size['width'], $size['height']), ImageInterface::THUMBNAIL_OUTBOUND)
            ->show($type);
    }

    /**
     * Format filename File.Ext to FileWxH.Ext
     * @param string $fileName file name
     * @param array $size size parameters ['width' => xxx, 'height' => yyy]
     * @return string new name
     */
    public static function thumbnailName($fileName, $size)
    {
        $fileParts = pathinfo($fileName);
        return sprintf($fileParts['filename'] . '_%dx%d.' . $fileParts['extension'], $size['width'], $size['height']);
    }
}

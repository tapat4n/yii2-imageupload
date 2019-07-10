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
use \Imagine\Image\Color;
use \Imagine\Image\ImageInterface;
use \Imagine\Image\Palette\RGB;

/**
 * @author Sergii Kozin <sergagame1@gmail.com>
 * @since 2.0
 */
class Image
{
    /**
     * GD2 driver definition for Imagine implementation using the GD library.
     */
    const DRIVER_GD2 = 'gd2';
    /**
     * imagick driver definition.
     */
    const DRIVER_IMAGICK = 'imagick';
    /**
     * gmagick driver definition.
     */
    const DRIVER_GMAGICK = 'gmagick';

    /**
     * @var array|string the driver to use. This can be either a single driver name or an array of driver names.
     * If the latter, the first available driver will be used.
     */
    public static $driver = [self::DRIVER_GMAGICK, self::DRIVER_IMAGICK, self::DRIVER_GD2];

    /* @var \Imagine\Gd\Imagine $_image */
    private $_image;

    /**
     * @var string background color to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET` mode with
     */
    public static $thumbnailBackgroundColor = 'FFF';

    /**
     * @var string background alpha (transparency) to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET
     */
    public static $thumbnailBackgroundAlpha = 100;

    /**
     * Creates an `Image` object based on the image model parameters .
     * @param array $options for construction the object
     */
    public function __construct($options)
    {
        $imagePath = $options['imagePath'];
        $imageFile = $options['imageFile'];
        $parameters = Json::decode($options['parameters']);

        // parameters preparing
        $parameters['data']['_width'] = $parameters['data']['width'];
        $parameters['data']['_height'] = $parameters['data']['height'];
        $parameters['data']['_x'] = 0;
        $parameters['data']['_y'] = 0;
        $parameters['data']['x'] = floor($parameters['data']['x']);
        $parameters['data']['y'] = floor($parameters['data']['y']);

        if ($parameters['data']['x'] < 0) {
            $parameters['data']['_width'] += $parameters['data']['x'];
            $parameters['data']['_x'] = -$parameters['data']['x'];
            $parameters['data']['x'] = 0;
        }

        if ($parameters['data']['x'] + $parameters['data']['width'] > $parameters['canvas']['naturalWidth']) {
            $parameters['data']['_width'] = $parameters['data']['width'] - ($parameters['data']['x'] + $parameters['data']['width'] - $parameters['canvas']['naturalWidth']);
        }

        if ($parameters['data']['y'] < 0) {
            $parameters['data']['_height'] += $parameters['data']['y'];
            $parameters['data']['_y'] = -$parameters['data']['y'];
            $parameters['data']['y'] = 0;
        }

        if ($parameters['data']['y'] + $parameters['data']['height'] > $parameters['canvas']['naturalHeight']) {
            $parameters['data']['_height'] = $parameters['data']['height'] - ($parameters['data']['y'] + $parameters['data']['height'] - $parameters['canvas']['naturalHeight']);
        }

        $canvas  = (new Imagine())->create(
            new Box($parameters['data']['width'], $parameters['data']['height']),
            (new RGB())->color(static::$thumbnailBackgroundColor, static::$thumbnailBackgroundAlpha)
        );

        $image = static::createImagine()->open($imagePath . '/' . $imageFile);
        $image->crop(new Point($parameters['data']['x'], $parameters['data']['y']), new Box($parameters['data']['_width'], $parameters['data']['_height']));
        $this->_image = $canvas->paste($image, new Point($parameters['data']['_x'], $parameters['data']['_y']))->copy();
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
        $this->_image->getThumbnail($size)->save($path . '/' . $file, $options);
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
        return $this->_image->show($type)->getThumbnail($size)->show($type);
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

    /**
     * Retun cropped image
     * @return \Imagine\Gd\Imagine
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * Gets thumbnail resource
     * @param $size
     * @return \Imagine\Gd\Image
     */
    public function getThumbnail($size)
    {
        return $this->_image->thumbnail(new Box($size['width'], $size['height']), ImageInterface::THUMBNAIL_OUTBOUND);
    }

    /**
     * Creates an `Imagine` object based on the specified [[driver]].
     * @return ImagineInterface the new `Imagine` object
     * @throws InvalidConfigException if [[driver]] is unknown or the system doesn't support any [[driver]].
     */
    protected static function createImagine()
    {
        foreach ((array) static::$driver as $driver) {
            switch ($driver) {
                case self::DRIVER_GMAGICK:
                    if (class_exists('Gmagick', false)) {
                        return new \Imagine\Gmagick\Imagine();
                    }
                    break;
                case self::DRIVER_IMAGICK:
                    if (class_exists('Imagick', false)) {
                        return new \Imagine\Imagick\Imagine();
                    }
                    break;
                case self::DRIVER_GD2:
                    if (function_exists('gd_info')) {
                        return new \Imagine\Gd\Imagine();
                    }
                    break;
                default:
                    throw new InvalidConfigException("Unknown driver: $driver");
            }
        }
        throw new InvalidConfigException('Your system does not support any of these drivers: ' . implode(',', (array) static::$driver));
    }
}

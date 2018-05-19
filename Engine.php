<?php

namespace Acms\Plugins\ImagePlaceholder;

require_once dirname(__FILE__) . '/lib/Potracio.php';

use Potracio\Potracio as Potracio;
use ColorThief\ColorThief;
use Acms\Services\Facades\Storage;
use Imagick;

class Engine
{
    /**
     * @var array
     */
    protected $tempImage = array();

    /**
     * シルエットのSVGを生成
     *
     * @param $src
     * @param $dest
     * @param string $color
     */
    public function createSilhouetteImage($src, $dest, $color = '#C5D2DA')
    {
//        $color = $this->getDominantColor($src);
        $tmp = $this->getBinarizationImage($src);
        $tmp = $this->getJpeg($tmp);

        $pot = new Potracio();
        $pot->loadImageFromFile($tmp);
        $pot->process();
        $svg = $pot->getSVG(1, '', '#FFFFFF', $color);

        Storage::put($dest, $svg);
        $this->removeTempImage();
    }

    /**
     * 塗りつぶしのSVGを生成
     *
     * @param $src
     * @param $dest
     */
    public function createFillColorImage($src, $dest)
    {
        $dominantColor = $this->getDominantColor($src);
        $size = $this->getImageSize($src);
        $svg = '<svg version="1.1" width="' . $size['width'] . '" height="' . $size['height'] . '" style="background-color: ' . $dominantColor . ' " xmlns="http://www.w3.org/2000/svg"></svg>';

        Storage::put($dest, str_replace('"', '\'', $svg));
    }

    /**
     * ぼかし画像を生成
     *
     * @param $src
     * @param $dest
     */
    public function createBlurImage($src, $dest)
    {
        $this->removeTempImage();
    }

    /**
     * メインカラーの取得
     *
     * @param $path
     * @return string
     */
    protected function getDominantColor($path)
    {
        $ary = ColorThief::getColor($path);
        $dominantColor = '#' . dechex($ary[0]) . dechex($ary[1]) . dechex($ary[2]);

        return $dominantColor;
    }

    /**
     * 画像サイズの取得
     *
     * @param $path
     * @return array
     */
    protected function getImageSize($path)
    {
        $size = getimagesize($path);

        return array(
            'width' => $size[0],
            'height' => $size[1],
        );
    }

    /**
     * 一時画像の削除
     */
    protected function removeTempImage()
    {
        $ary = array_unique($this->tempImage);
        foreach ( $ary as $path ) {
            Storage::remove($path);
        }
    }

    /**
     * JPEGへの変換
     *
     * @param $path
     * @return string
     */
    protected function getJpeg($path)
    {
        $img_data = file_get_contents($path);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $img_data);
        finfo_close($finfo);

        if ( 'image/gif' === $mime_type ) {
            imagejpeg(imagecreatefromgif($path), $path);
        } else if ( 'image/png' === $mime_type ) {
            imagejpeg(imagecreatefrompng($path), $path);
        } else if ( 'image/bmp' === $mime_type ) {
            imagejpeg(imagecreatefromwbmp($path), $path);
        } else if ( 'image/xbm' === $mime_type ) {
            imagejpeg(imagecreatefromxbm($path), $path);
        }

        return $path;
    }

    /**
     * 2値化された画像の取得
     *
     * @param string $path
     * @param string $thresholdColor
     * @return string
     */
    protected function getBinarizationImage($path, $thresholdColor = '#606060')
    {
        $tmp = ARCHIVES_DIR . uniqueString();

        $imagick = new Imagick($path);
        $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY);
        $imagick->blackThresholdImage($thresholdColor);
        $imagick->whiteThresholdImage($thresholdColor);
        $imagick->blurImage(1,1);
        $imagick->writeImage($tmp);
        $imagick->destroy();

        $this->tempImage[] = $tmp;

        return $tmp;
    }
}
<?php

namespace Acms\Plugins\ImagePlaceholder;

class Corrector
{
    public static function dataUrlSvg($path)
    {
        $normalPath = normalSizeImagePath($path);
        $svgPath = trim(dirname($normalPath), '/').'/silhouette-'.basename($normalPath);
        $svgPath = preg_replace('/\.[^\.]+$/', '.svg', $svgPath);

        if ( !is_readable($svgPath) ) {
            return $path;
        }
        return 'data:image/svg+xml;charset=utf8,' . file_get_contents($svgPath);
    }

    public static function fillCollorImage($path)
    {
        $normalPath = normalSizeImagePath($path);
        $svgPath = trim(dirname($normalPath), '/').'/fill-'.basename($normalPath);
        $svgPath = preg_replace('/\.[^\.]+$/', '.svg', $svgPath);

        if ( !is_readable($svgPath) ) {
            return $path;
        }
        return 'data:image/svg+xml;charset=utf8,' . file_get_contents($svgPath);
    }

}
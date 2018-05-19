<?php

namespace Acms\Plugins\SamplePlugin;

use App;
use Acms\Services\Facades\Storage;

class Hook
{
    /**
     * メディアデータ作成
     * @param string $path 作成先パス
     */
    public function mediaCreate($path)
    {
        $engine = App::make('image_placeholder');

        if ( !@getimagesize($path) ) {
            return;
        }
        $normalPath = normalSizeImagePath($path);

        if ( $normalPath !== $path ) {
            return; // 通常画像のみ実行
        }

        // シルエット画像の生成
        $dest = trim(dirname($path), '/').'/silhouette-'.basename($path);
        $dest = preg_replace('/\.[^\.]+$/', '.svg', $dest);

        if ( !Storage::exists($dest) && strpos($dest, REVISON_ARCHIVES_DIR) === false ) {
            $engine->createSilhouetteImage($path, $dest);
        }

        // 単色画像の生成
        $dest = trim(dirname($path), '/').'/fill-'.basename($path);
        $dest = preg_replace('/\.[^\.]+$/', '.svg', $dest);

        if ( !Storage::exists($dest) && strpos($dest, REVISON_ARCHIVES_DIR) === false ) {
            $engine->createFillColorImage($path, $dest);
        }
    }
}
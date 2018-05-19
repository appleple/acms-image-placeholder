<?php

namespace Acms\Plugins\ImagePlaceholder;

use ACMS_App;
use Acms\Services\Common\HookFactory;

class ServiceProvider extends ACMS_App
{
    /**
     * @var string
     */
    public $version = '1.0.0';

    /**
     * @var string
     */
    public $name = 'ImagePlaceholder';

    /**
     * @var string
     */
    public $author = 'com.appleple';

    /**
     * @var bool
     */
    public $module = false;

    /**
     * @var bool|string
     */
    public $menu = false;

    /**
     * @var string
     */
    public $desc = '画像のプレイスホルダーを生成します。';

    /**
     * サービスの初期処理
     */
    public function init()
    {
        require_once dirname(__FILE__).'/vendor/autoload.php';

        $hook = HookFactory::singleton();
        $hook->attach('ImagePlaceholderHook', new Hook);
    }

    /**
     * インストールする前の環境チェック処理
     *
     * @return bool
     */
    public function checkRequirements()
    {
        return true;
    }

    /**
     * インストールするときの処理
     * データベーステーブルの初期化など
     *
     * @return void
     */
    public function install()
    {

    }

    /**
     * アンインストールするときの処理
     * データベーステーブルの始末など
     *
     * @return void
     */
    public function uninstall()
    {

    }

    /**
     * アップデートするときの処理
     *
     * @return bool
     */
    public function update()
    {
        return true;
    }

    /**
     * 有効化するときの処理
     *
     * @return bool
     */
    public function activate()
    {
        return true;
    }

    /**
     * 無効化するときの処理
     *
     * @return bool
     */
    public function deactivate()
    {
        return true;
    }
}
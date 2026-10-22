<?php

namespace cuzyapp\suneditor\assets;

use Composer\InstalledVersions;
use humhub\components\assets\AssetBundle;
use Yii;

/**
 * SunEditor's *content* stylesheet — the rules that style what the editor
 * produced, without any of the editor chrome in {@see SuneditorFieldAssets} —
 * plus this package's own overrides: `theme.css`, which maps SunEditor's
 * `--se-*` design tokens onto HumHub's theme (shared with the field bundle),
 * and `content.css`, specific to read-only rendering.
 *
 * Registered by {@see \cuzyapp\suneditor\widgets\SuneditorContent}, which is
 * what puts the `.sun-editor-editable` class these rules are scoped to on the
 * wrapper. Every rule in SunEditor's own file sits under that class, so it
 * stays inside its wrapper — but its colours, font and padding are SunEditor's
 * own fixed defaults (a white, padded box of 16px `Helvetica Neue`), not
 * inherited from the page, which is what `theme.css` corrects.
 */
class SuneditorContentAssets extends AssetBundle
{
    public $sourcePath = __DIR__ . '/../resources';

    public $css = [
        'css/theme.css',
        'css/content.css',
    ];

    public function init()
    {
        parent::init();

        $suneditorDist = InstalledVersions::getInstallPath('npm-asset/suneditor') . '/dist';
        [, $suneditorUrl] = Yii::$app->assetManager->publish($suneditorDist);
        // See the identical line in SuneditorFieldAssets::init() for why this is
        // necessary: AssetManager::publish() does not resolve aliases in the URL
        // it returns, and HumHub's AssetManager returns the asset mount's raw
        // `@web/...` alias here.
        $suneditorUrl = Yii::getAlias($suneditorUrl);

        // Prepended: the overrides in $css need SunEditor's own rules loaded first.
        array_unshift($this->css, $suneditorUrl . '/suneditor-contents.min.css');
    }
}

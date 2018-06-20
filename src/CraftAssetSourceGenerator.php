<?php
/**
 * Craft Asset Source Generator plugin for Craft CMS 3.x
 *
 * Creates configured asset sources when the plugin is first installed
 *
 * @link      https://twitter.com/servdhosting
 * @copyright Copyright (c) 2018 Matt Gray
 */

namespace servd\craftassetsourcegenerator;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\elements\Asset;
use craft\helpers\DateTimeHelper;

use yii\base\Event;

/**
 * Class CraftAssetSourceGenerator
 *
 * @author    Matt Gray
 * @package   CraftAssetSourceGenerator
 * @since     0.1.0
 *
 */
class CraftAssetSourceGenerator extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CraftAssetSourceGenerator
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '0.1.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $settings = $this->getSettings();
        //Setting have been loaded from file
        if (sizeof($settings->sources) > 0) {
            //Get existing volumes
            $existingVolumes = Craft::$app->getVolumes()->getAllVolumes();
            //Loop over our intended volumes
            foreach ($settings->sources as $source) {
                $handle = $source['handle'];
                $found = false;
                //Check if it exists
                foreach ($existingVolumes as $existingVolume) {
                    if ($existingVolume->handle == $handle) {
                        $found = true;
                        break;
                    }
                }
                //If it doesn't exist, create it
                if (!$found) {
                    $this->createVolume($source);
                }
            }
        }

        if ($settings->preventDisable) {
            Event::on(
                Plugins::class,
                Plugins::EVENT_BEFORE_DISABLE_PLUGIN,
                function (PluginEvent $event) {
                    if ($event->plugin === $this) {
                        echo('Plugin can\'t be disabled');
                        exit;
                    }
                }
            );
        }

        Craft::info(
            Craft::t(
                'craft-asset-source-generator',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function createVolume($source)
    {

        Craft::info(
            Craft::t(
                'craft-asset-source-generator',
                'Creating volume {name}',
                ['name' => $source['name']]
            ),
            __METHOD__
        );
        $volumes = Craft::$app->getVolumes();
        $type = 'craft\awss3\Volume';
        $volume = $volumes->createVolume([
            'id' => null,
            'type' => $type,
            'name' => $source['name'],
            'handle' => $source['handle'],
            'hasUrls' => $source['hasUrls'],
            'url' => $source['url'],
            'settings' => [
                'keyId' => $source['keyId'],
                'secret' => $source['secret'],
                'bucket' => $source['bucket'],
                'region' => $source['region'],
                'subfolder' => $source['subfolder'],
                'expires' => $source['expires'],
                'cfDistributionId' => $source['cfDistributionId'],
            ]
        ]);

        if (!$volumes->saveVolume($volume)) {
            //Error creating volume
        }
    }

    public function beforeUninstall(): bool
    {
        $settings = $this->getSettings();
        if ($settings->preventUninstall) {
            return false;
        }
        return parent::beforeUninstall();
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new \servd\craftassetsourcegenerator\models\Settings();
    }
}

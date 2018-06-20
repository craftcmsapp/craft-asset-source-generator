<?php
/**
 * Craft Asset Source Generator plugin for Craft CMS 3.x
 *
 * Creates configured asset sources when the plugin is first installed
 *
 * @link      https://twitter.com/servdhosting
 * @copyright Copyright (c) 2018 Matt Gray
 */

namespace servd\craftassetsourcegenerator\models;

use servd\craftassetsourcegenerator\CraftAssetSourceGenerator;

use Craft;
use craft\base\Model;

/**
 * @author    Matt Gray
 * @package   CraftAssetSourceGenerator
 * @since     0.1.0
 */
class Settings extends Model
{
    public $sources = [
    ];
    public $preventUninstall = false;
    public $preventDisable = false;

    public function rules()
    {
        return [
            [['sources'], 'required'],
        ];
    }
}

<?php
/**
 * URL2PNG plugin for Craft CMS 3.x
 *
 * Lets you embed screenshots of webpages via the URL2PNG API.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\url2png;

use superbig\url2png\services\URL2PNGService as URL2PNGServiceService;
use superbig\url2png\variables\URL2PNGVariable;
use superbig\url2png\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class URL2PNG
 *
 * @author    Superbig
 * @package   URL2PNG
 * @since     1.0.0
 *
 * @property  URL2PNGServiceService $service
 * @method   Settings              getSettings()
 */
class URL2PNG extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var URL2PNG
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('url2png', URL2PNGVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'url2png',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'url2png/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}

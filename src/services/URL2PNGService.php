<?php
/**
 * URL2PNG plugin for Craft CMS 3.x
 *
 * Lets you embed screenshots of webpages via the URL2PNG API.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\url2png\services;

use superbig\url2png\URL2PNG;

use Craft;
use craft\base\Component;
use yii\base\Exception;

/**
 * @author    Superbig
 * @package   URL2PNG
 * @since     1.0.0
 */
class URL2PNGService extends Component
{

    protected $settings;
    protected $allowedOptions;

    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->settings = URL2PNG::$plugin->getSettings();

        // Set allowed options
        $this->setAllowedOptions();
    }

    public function create($options)
    {
        # Get your apikey from http://url2png.com/plans
        $apiKey    = $this->settings->apiKey;
        $apiSecret = $this->settings->apiSecret;

        // Clean options
        $options = $this->cleanPassedOptions($options, $this->allowedOptions);

        if (!is_array($options) && in_array('url', $options)) {
            throw new Exception(Craft::t('url2png', 'url2png: No url defined'));
        }

        # create the query string based on the options
        foreach ($options as $key => $value) {
            $_parts[] = "$key=$value";
        }

        # create a token from the ENTIRE query string
        $query_string = implode("&", $_parts);
        $token        = md5($query_string . $apiSecret);

        return "https://api.url2png.com/v6/{$apiKey}/{$token}/png/?{$query_string}";
    }

    private function setAllowedOptions()
    {
        $this->allowedOptions = [
            'url',
            'thumbnail_max_width', // Default 1:1, can be something like 500
            'viewport', // Defaults to 1480x1037
            'fullpage', // Defaults to false
            'unique', // Forces a fresh screenshot by sending a unique value. A timestamp is suggested.
            'user_agent', // Custom user agent
            'accept_languages', // Accept-Language header. Defaults to en-US,en;q=0.8
            'custom_css_url', // Fetches a CSS stylesheet and injects it
            'say_cheese', // Delay screenshot until <div id='url2png-cheese'></div> is available.
            'ttl', // Set the TTL or "time to live" value for a screenshot in seconds. Defaults to 2592000 (30 days)
        ];
    }

    private function cleanPassedOptions($options, $allowedOptions)
    {
        $cleanOptions = [];

        foreach ($options as $key => $value) {
            // Check if this option is allowed
            if (!in_array($key, $allowedOptions)) {
                Craft::error(
                    Craft::t('url2png', 'Option {key} not allowed', ['key' => $key]),
                    'url2png'
                );

                continue;
            }

            // If this is url, urlencode it
            if ($key === 'url') $value = urlencode($value);

            $cleanOptions[ $key ] = $value;
        }

        return $cleanOptions;
    }

    public function imgAttributes($options = [])
    {
        $allowedAttributes = [
            'class',
            'width',
            'height',
            'alt',
        ];
        $attributes        = $this->cleanPassedOptions($options, $allowedAttributes);

        if (empty($attributes)) {
            return '';
        }

        $attributes = array_map(function($key, $value) {
            return "{$key}=\"{$value}\"";
        }, array_keys($attributes), $attributes);

        return implode(' ', $attributes);
    }
}

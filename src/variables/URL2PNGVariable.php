<?php
/**
 * URL2PNG plugin for Craft CMS 3.x
 *
 * Lets you embed screenshots of webpages via the URL2PNG API.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\url2png\variables;

use craft\helpers\Template;
use superbig\url2png\URL2PNG;

use Craft;

/**
 * @author    Superbig
 * @package   URL2PNG
 * @since     1.0.0
 */
class URL2PNGVariable
{
    // Public Methods
    // =========================================================================

    public function img($settings = [])
    {
        $src        = $this->_run($settings);
        $attributes = $this->imgAttributes($settings);
        $html       = "<img src=\"{$src}\"{$attributes}>";

        return Template::raw($html);
    }

    public function url($settings = [])
    {
        return $this->_run($settings);
    }

    private function _run($settings)
    {
        return URL2PNG::$plugin->service->create($settings);
    }

    private function imgAttributes($settings)
    {
        return URL2PNG::$plugin->service->imgAttributes($settings);
    }
}

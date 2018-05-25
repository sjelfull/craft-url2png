<?php
/**
 * URL2PNG plugin for Craft CMS 3.x
 *
 * Lets you embed screenshots of webpages via the URL2PNG API.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\url2png\models;

use superbig\url2png\URL2PNG;

use Craft;
use craft\base\Model;

/**
 * @author    Superbig
 * @package   URL2PNG
 * @since     1.0.0
 */
class URL2PNGModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}

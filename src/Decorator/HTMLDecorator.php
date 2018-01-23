<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Decorator;

/**
 * Class HTMLDecorator
 *
 * Decorate a resource url into a valid HTML string
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class HTMLDecorator
{
    /**
     * The url to decorate
     *
     * @var string
     */
    protected $url;

    /**
     * Defines some attributes alias to rewrite before decorate
     *
     * @var array
     */
    protected static $attributesAlias = [
        'html_width' => 'width',
        'html_height' => 'height',
    ];

    /**
     * Build an instance of HTMLDecorator.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Decorate the url
     *
     * @return string
     */
    public function decorate($resourceType, array $options)
    {
        return sprintf(
            $this->getHTMLContent($resourceType),
            $this->url,
            $this->getHTMLAttributes($options)
        );
    }

    /**
     * Return the attributes to display on the HTML element
     *
     * @param array $options
     *
     * @return string
     */
    protected function getHTMLAttributes(array $options)
    {
        unset($options['resource_type']);
        unset($options['sign_url']);
        unset($options['secure']);

        $attributes = [];
        foreach ($options as $optionName => $optionValue) {
            if (array_key_exists($optionName, self::$attributesAlias)) {
                $optionName = self::$attributesAlias[$optionName];
            }

            $attributes[] = sprintf('%s="%s"', $optionName, $optionValue);
        }

        return implode(' ', $attributes);
    }

    /**
     * Get the HTML content based on the resource type
     *
     * @param string $resourceType
     *
     * @return string
     */
    protected function getHTMLContent($resourceType)
    {
        switch ($resourceType) {
            case 'image':
                return '<img src="%s" %s />';
                break;
            case 'video':
                return '<video src="%s" %s />';
                break;
            default:
                return '';
                break;
        }
    }
}
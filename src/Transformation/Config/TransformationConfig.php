<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation\Config;
use Scc\Cdn\Transformation\Type;

/**
 * Class TransformationConfig
 *
 * Defines the transformation configuration
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TransformationConfig
{
    const RESOURCES_TYPES = [
        'image' => [
            Type\Background::class,
            Type\Border::class,
            Type\Color::class,
            Type\CoordX::class,
            Type\CoordY::class,
            Type\Crop::class,
            Type\Effect::class,
            Type\FetchFormat::class,
            Type\Gravity::class,
            Type\Height::class,
            Type\Opacity::class,
            Type\Quality::class,
            Type\Width::class
        ],
        'video' => [
            Type\Crop::class,
            Type\FetchFormat::class,
            Type\Height::class,
            Type\Quality::class,
            Type\Width::class
        ],
        'file' => []
    ];

    /**
     * Get a configuration by resource type
     *
     * @param string $resourceType
     *
     * @return array
     */
    public static function getConfiguration($resourceType)
    {
        if (!isset(static::RESOURCES_TYPES[$resourceType])) {
            throw new \InvalidArgumentException(sprintf(
                'The "%s" resource type is not valid. Valid values are "%s"',
                $resourceType,
                implode(', ', array_keys(static::RESOURCES_TYPES))
            ));
        }

        return static::RESOURCES_TYPES[$resourceType];
    }

    /**
     * Get the different resources types
     *
     * @return array
     */
    public static function getResourcesTypes()
    {
        $resources = [];

        foreach (array_keys(static::RESOURCES_TYPES) as $resource) {
            $resources[$resource] = $resource;
        }

        return $resources;
    }
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation;

use Scc\Cdn\Transformation\Config\TransformationConfig;
use Scc\Cdn\Validator\ResourceTypeValidator;

/**
 * Class TransformationManager
 *
 * Handle the business process of the transformations
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TransformationManager
{
    const TRANSFORMATIONS_SEPARATOR = ',';

    const RESOURCES_TYPES = [
        'image' => ImageTypeInterface::class,
        'video' => VideoTypeInterface::class,
        'file' => FileTypeInterface::class
    ];

    /**
     * The pool of transformations
     *
     * @var TransformationPool
     */
    protected $pool;

    /**
     * The attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Build an instance of TransformationManager.
     */
    public function __construct()
    {
        $this->initPool();
    }

    /**
     * Init the pool
     *
     * @return $this
     */
    public function initPool()
    {
        $this->pool = new TransformationPool();

        return $this;
    }

    /**
     * Get the attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Convert a string from snake case to camel case
     *
     * @param string $string
     *
     * @return string
     */
    protected function dashesToCamelCase($string)
    {
        return str_replace('_', '', ucwords($string, '_'));
    }

    /**
     * Resolve the transformations and validate if accessible
     *
     * @param string $resourceType
     * @param array  $options
     *
     * @return $this
     */
    public function resolveTransformations($resourceType, array $options)
    {
        $this->validate($resourceType);
        $this->initPool();
        $this->attributes = [];

        foreach ($options as $key => $value) {
            $className = 'Scc\\Cdn\\Transformation\\Type\\' . $this->dashesToCamelCase($key);

            if (!class_exists($className)) {
                $this->attributes[$key] = $value;
                continue;
            }

            if (is_subclass_of($className, static::RESOURCES_TYPES[$resourceType])) {
                $this->pool->addTransformation(new $className());
            }
        }

        return $this;
    }

    /**
     * Get a string representation of the transformations
     *
     * @param array $options
     *
     * @return string
     */
    public function stringifyTransformations(array $options)
    {
        $strings = [];
        foreach ($this->pool->getTransformations() as $transformation) {
            /** @var TransformationInterface $transformation */
            if (isset($options[$transformation->getName()])) {
                $strings[] = $transformation->stringify($options[$transformation->getName()]);
            }
        }

        sort($strings);

        return implode(static::TRANSFORMATIONS_SEPARATOR, $strings);
    }

    /**
     * Validation
     *
     * @param string $resourceType
     */
    protected function validate($resourceType)
    {
        if (!isset(static::RESOURCES_TYPES[$resourceType])) {
            throw new \InvalidArgumentException(sprintf(
                'The "%s" resource type is not valid. Valid values are "%s"',
                $resourceType,
                implode(', ', array_keys(static::RESOURCES_TYPES))
            ));
        }
    }
}
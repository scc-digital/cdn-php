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

        $availableTransformations = TransformationConfig::getConfiguration($resourceType);

        foreach ($availableTransformations as $transformationClass) {
            /** @var TransformationInterface $transformation */
            $transformation = new $transformationClass();

            if (in_array($transformation->getName(), array_keys($options))) {
                $this->pool->addTransformation($transformation);
                unset($options[$transformation->getName()]);
                continue;
            }

            unset($transformation);
        }

        $this->attributes = $options;

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
     * @param       $resourceType
     */
    protected function validate($resourceType)
    {
        ResourceTypeValidator::validate($resourceType);
    }
}
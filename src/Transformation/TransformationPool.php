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
use Scc\Cdn\Transformation\Exception\UnsupportedException;

/**
 * Class TransformationPool
 *
 * The Pool of the available transformations
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TransformationPool
{
    /**
     * @var \SplObjectStorage
     */
    protected $transformations;

    /**
     * Build an instance of TransformationPool.
     */
    public function __construct()
    {
        $this->transformations = new \SplObjectStorage();
    }

    /**
     * Get the transformations
     *
     * @return \SplObjectStorage
     */
    public function getTransformations()
    {
        return $this->transformations;
    }

    /**
     * Get a transformation for the given name
     *
     * @param string $name
     *
     * @return TransformationInterface
     *
     * @throws UnsupportedException
     */
    public function getTransformationByName($name)
    {
        foreach ($this->transformations as $transformation) {
            /** @var TransformationInterface $transformation */
            if ($transformation->getName() === $name) {
                return $transformation;
            }
        }

        throw new UnsupportedException($name);
    }

    /**
     * Get a transformation for the given alias
     *
     * @param string $alias
     *
     * @return TransformationInterface
     *
     * @throws UnsupportedException
     */
    public function getTransformationByAlias($alias)
    {
        foreach ($this->transformations as $transformation) {
            /** @var TransformationInterface $transformation */
            if ($transformation->getAlias() === $alias) {
                return $transformation;
            }
        }

        throw new UnsupportedException($alias);
    }

    /**
     * Add a new transformation to the pool
     *
     * @param TransformationInterface $transformation
     *
     * @return $this
     */
    public function addTransformation(TransformationInterface $transformation)
    {
        if (!$this->transformations->contains($transformation)) {
            try {
                $this->getTransformationByName($transformation->getName());
                $this->getTransformationByAlias($transformation->getAlias());
            } catch (UnsupportedException $exception) {
                $this->transformations->attach($transformation);
            }
        }

        return $this;
    }

    /**
     * Remove a transformation from the pool
     *
     * @param TransformationInterface $transformation
     *
     * @return $this
     */
    public function removeTransformation(TransformationInterface $transformation)
    {
        if ($this->transformations->contains($transformation)) {
            $this->transformations->detach($transformation);
        }

        return $this;
    }
}

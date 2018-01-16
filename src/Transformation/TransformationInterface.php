<?php

declare (strict_types=1);

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation;

/**
 * Interface TransformationInterface
 *
 * Implemented by each available transformation
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
interface TransformationInterface
{
    /**
     * Get the transformation name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the transformation alias
     *
     * @return string
     */
    public function getAlias();

    /**
     * Return a string representation of the current transformation
     *
     * @param string|integer $value
     *
     * @return string
     */
    public function stringify($value);
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation\Exception;

/**
 * Class UnsupportedTransformation
 *
 * Thrown when a transformation is not found
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>µ
 */
class UnsupportedException extends \LogicException
{
    /**
     * @var string
     */
    protected $transformation;

    /**
     * Build an instance of UnsupportedTransformation.
     *
     * @param string $transformation
     */
    public function __construct($transformation)
    {
        parent::__construct(sprintf('The transformation "%s" is not supported', $transformation));
        $this->transformation = $transformation;
    }

    /**
     * Get the transformation
     *
     * @return string
     */
    public function getTransformation()
    {
        return $this->transformation;
    }
}

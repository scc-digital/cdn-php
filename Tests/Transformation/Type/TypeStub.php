<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Transformation\Type;

use Scc\Cdn\Transformation\Type\AbstractType;

/**
 * Class TypeStub
 *
 * Stub for Transformation Type
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TypeStub extends AbstractType
{
    /**
     * Build an instance of TypeStub.
     *
     * @param string $transformationName
     */
    public function __construct($transformationName)
    {
        define('TRANSFORMATION_NAME', $transformationName);
    }
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Transformation\Exception;

use Scc\Cdn\Tests\Helper\Traits\ReflectionTrait;
use Scc\Cdn\Transformation\Exception\UnsupportedException;

/**
 * Class UnsupportedExceptionTest
 *
 * Test the class UnsupportedException
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class UnsupportedExceptionTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * Provide tests for the whole class
     */
    public function testWholeClass()
    {
        $instance = new UnsupportedException('test_transformation');

        $propertyValue = $this->getProperty($instance, 'transformation');

        $this->assertInstanceOf(\LogicException::class, $instance);
        $this->assertSame(sprintf('The transformation "%s" is not supported', 'test_transformation'), $instance->getMessage());
        $this->assertSame('test_transformation', $propertyValue);
        $this->assertSame('test_transformation', $instance->getTransformation());
    }
}

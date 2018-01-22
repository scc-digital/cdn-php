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
use Scc\Cdn\Transformation\Exception\UndefinedException;
use Scc\Cdn\Transformation\Type\Width;

/**
 * Class UndefinedExceptionTest
 *
 * Test the class UndefinedException
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class UndefinedExceptionTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * Provide tests for the whole class
     */
    public function testWholeClass()
    {
        $testTransformation = new Width();

        $instance = new UndefinedException($testTransformation);

        $propertyValue = $this->getProperty($instance, 'transformation');

        $this->assertInstanceOf(\LogicException::class, $instance);
        $this->assertSame('The transformation have no name', $instance->getMessage());
        $this->assertSame($testTransformation, $propertyValue);
        $this->assertSame($testTransformation, $instance->getTransformation());
    }
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Exception;

use Scc\Cdn\Exception\MissingOptionException;

/**
 * Class MissingOptionExceptionTest
 *
 * Test class for MissingOptionException::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class MissingOptionExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the MissingOptionException class
     */
    public function testWholeClass()
    {
        $instance = new MissingOptionException('foo');

        $this->assertSame('foo', $instance->getOption());
        $this->assertSame('The option "foo" have to be set', $instance->getMessage());
    }
}
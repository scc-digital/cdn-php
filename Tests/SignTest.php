<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests;

use Scc\Cdn\Sign;

/**
 * Class SignTest
 *
 * @author Jerôme Fix <jerome.fix@sccd.lu>
 */
class SignTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the generate function
     *
     * @dataProvider providerGenerate
     */
    public function testGenerate($apiSecret, $transformations, $source, $expected)
    {
        $sign = new Sign($apiSecret);
        self::assertSame($expected, $sign->generate($transformations, $source));

    }

    /**
     * Data provider
     *
     * @return array
     */
    public function providerGenerate ()
    {
        return [
            ['1234', [], 'a-path', 's--a01645ad--']
        ];
    }
}
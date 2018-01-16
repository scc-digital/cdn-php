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

namespace Scc\Cdn\Tests;

use Scc\Cdn\Sign;

class SignTest   extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerGenerate
     */
    public function testGenerate($api_secret, $transformations, $source,  $expected )
    {

        $sign = new Sign($api_secret);
        self::assertSame($expected, $sign->generate($transformations, $source));

    }


    public function providerGenerate () {
        return [
            ['1234', [], 'a-path', 's--oBZFrWXN--'],
         ];
}
}
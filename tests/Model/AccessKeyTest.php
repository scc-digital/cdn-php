<?php

/*
* This file is part of the Mall Digital Ecosystem (MDE) project. of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Model;

use Faker\Factory;
use Scc\Cdn\Model\AccessKey;

class AccessKeyTest  extends \PHPUnit_Framework_TestCase
{
    public function testSetterGetters()
    {
        $faker = Factory::create();

        $private_uuid = $faker->uuid;
        $public_uuid = $faker->uuid;

        $key= new AccessKey();

        self::assertInstanceOf(AccessKey::class, $key->setPrivate($private_uuid));
        self::assertSame($private_uuid, $key->getPrivate());

        self::assertInstanceOf(AccessKey::class, $key->setPublic($public_uuid));
        self::assertSame($public_uuid, $key->getPublic());
    }


}

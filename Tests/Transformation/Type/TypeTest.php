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
use Scc\Cdn\Tests\Helper\Traits\ReflectionTrait;
use Scc\Cdn\Transformation\Exception\UndefinedException;
use Scc\Cdn\Transformation\TransformationInterface;

/**
 * Class TypeTest
 *
 * This class provide generic tests for Transformation Types
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * Test the transformation types
     */
    public function testType()
    {
        $definedValues = ['names' => [], 'aliases' => []];

        foreach ($this->getInstanceOfTypes(TransformationInterface::class) as $className) {
            /** @var TransformationInterface $class */
            $class = new $className();

            try {
                $this->assertNotEmpty($class->getName());
                $this->assertNotEmpty($class->getAlias());
            } catch (UndefinedException $exception) {
                $this->fail(sprintf('No name or alias provided for the class %s', $className));
            }

            if (in_array($class->getName(), $definedValues['names'])) {
                $this->fail(sprintf('More than one class have to same name %s', $class->getName()));
            }

            if (in_array($class->getAlias(), $definedValues['aliases'])) {
                $this->fail(sprintf('More than one class have to same name %s', $class->getName()));
            }

            $this->assertSame($class->getAlias() . '_' . 'test', $class->stringify('test'));

            $definedValues['names'][] = $class->getName();
            $definedValues['aliases'][] = $class->getAlias();
        }
    }
}

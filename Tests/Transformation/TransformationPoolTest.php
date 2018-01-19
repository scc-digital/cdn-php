<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Transformation;

use Scc\Cdn\Transformation\Exception\UnsupportedException;
use Scc\Cdn\Transformation\TransformationInterface;
use Scc\Cdn\Transformation\TransformationPool;
use Scc\Cdn\Transformation\Type\Color;
use Scc\Cdn\Transformation\Type\Width;

/**
 * Class TransformationPoolTest
 *
 * Test class for TransformationPool::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TransformationPoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TransformationPool
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->instance = new TransformationPool();
    }

    /**
     * Property caller.
     *
     * @param mixed  $object
     * @param string $name
     * @param mixed  $value
     *
     * @return \ReflectionProperty
     */
    protected static function setProperty($object, string $name, $value)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $value);

        return $property;
    }

    /**
     * Property getter.
     *
     * @param mixed  $object
     * @param string $name
     *
     * @return mixed
     */
    protected static function getProperty($object, string $name)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Test the TransformationPool::__construct method
     */
    public function testConstruct()
    {
        $transformations = $this->getProperty($this->instance, 'transformations');

        $this->assertInstanceOf(\SplObjectStorage::class, $transformations);
        $this->assertEmpty($transformations);
    }

    /**
     * Test the TransformationPool::getTransformations method
     */
    public function testGetTransformations()
    {
        $storage = new \SplObjectStorage();
        $this->setProperty($this->instance, 'transformations', $storage);

        $this->assertSame($storage, $this->instance->getTransformations());
    }

    /**
     * Get some test transformations
     *
     * @return array
     */
    public function provideTransformations()
    {
        return [
            ['width', '', 'Name'],
            ['test_invalid_name', UnsupportedException::class, 'Name'],
            ['w', '', 'Alias'],
            ['test_invalid_alias', UnsupportedException::class, 'Alias'],
        ];
    }

    /**
     * Test the TransformationPool::getTransformationBy*** methods
     *
     * @param string $searchedValue
     * @param string $exception
     * @param string $subject
     *
     * @dataProvider provideTransformations
     */
    public function testGetTransformationByCriteria($searchedValue, $exception, $subject)
    {
        $mock = $this->getMockBuilder(TransformationInterface::class)
          ->getMock();

        $mock->expects($this->once())
          ->method('get' . $subject)
          ->willReturn(empty($exception) ? $searchedValue : 'other_value');

        $storage = new \SplObjectStorage();
        $storage->attach($mock);

        $this->setProperty($this->instance, 'transformations', $storage);

        $method = 'getTransformationBy' . $subject;

        if (empty($exception)) {
            $this->assertSame($mock, $this->instance->$method($searchedValue));
        } else {
            $this->expectException($exception);
            $this->instance->$method($searchedValue);
        }
    }

    /**
     * Test the TransformationPool::addTransformation, removeTransformation and count methods
     */
    public function testAddAndRemoveAndCountTransformation()
    {
        if (!$this->instance instanceof \Countable) {
            $this->fail(sprintf('The "%s" class must be an instance of %s', TransformationPool::class, \Countable::class));
        }

        $width = new Width();
        $color = new Color();
        $fakeWidth = new Width();

        $transformations = $this->getProperty($this->instance, 'transformations');

        $this->assertSame($this->instance, $this->instance->addTransformation($width));
        $this->assertContainsOnly($width, $transformations);

        $this->assertSame($this->instance, $this->instance->addTransformation($width));
        $this->assertContainsOnly($width, $transformations);

        $this->assertSame($this->instance, $this->instance->addTransformation($color));
        $this->assertEquals(2, count($transformations));
        $this->assertContains($width, $transformations);
        $this->assertContains($color, $transformations);

        $this->assertSame($this->instance, $this->instance->addTransformation($fakeWidth));
        $this->assertEquals(2, count($transformations));
        $this->assertContains($width, $transformations);
        $this->assertContains($color, $transformations);
        $this->assertNotContains($fakeWidth, $transformations);

        $this->assertSame($this->instance, $this->instance->removeTransformation($color));
        $this->assertContainsOnly($width, $transformations);
    }
}
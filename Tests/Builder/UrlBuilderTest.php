<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Builder;

/**
 * Class UrlBuilderTest
 *
 * Test class for UrlBuilder::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'my/path/to/test';

    /**
     * @var UrlBuilder
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->instance = new UrlBuilder(self::PATH);
    }

    /**
     * Test the UrlBuilder::__construct class
     */
    public function testConstruct()
    {
        $this->assertSame(self::PATH, $this->getProperty($this->instance, 'basePath'));
    }

    /**
     * Provide test cases for url parts add
     *
     * @return array
     */
    public function provideUrlParts()
    {
        return [
            [['part1', 'part2'], ''],
            [['part1', 'part2'], \RuntimeException::class],
        ];
    }

    /**
     * Property caller.
     *
     * @param mixed  $object
     * @param string $name
     * @param array  $value
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
     * Test the UrlBuilder::addUrlParts method
     *
     * @param array  $parts
     * @param string $exception
     *
     * @dataProvider provideUrlParts
     */
    public function testAddUrlPart(array $parts, $exception)
    {
        if (!empty($exception)) {
            $this->expectException($exception);

            foreach ($parts as $part) {
                $this->instance->addUrlPart(0, $part);
            }
        } else {
            foreach ($parts as $position => $part) {
                $this->assertSame($this->instance, $this->instance->addUrlPart($position, $part));
            }

            $this->assertSame($parts, $this->getProperty($this->instance, 'urlParts'));
        }
    }

    /**
     * Test the UrlBuilder::replaceUrlPart method
     */
    public function testReplaceUrlPart()
    {
        $reflectedProp = $this->setProperty($this->instance, 'urlParts', ['test_value_1', 'test_value_2']);

        $this->assertSame($this->instance, $this->instance->replaceUrlPart(0, 'test_replacement_value'));
        $this->assertSame(['test_replacement_value', 'test_value_2'], $reflectedProp->getValue($this->instance));
    }

    /**
     * Test the UrlBuilder::getBasePath method
     */
    public function testGetBasePath()
    {
        $this->assertSame(self::PATH, $this->instance->getBasePath());

    }

    /**
     * Test the UrlBuilder::setBasePath method
     */
    public function testSetBasePath()
    {
        $this->assertSame($this->instance, $this->instance->setBasePath('replace/path'));
        $this->assertSame('replace/path', $this->getProperty($this->instance, 'basePath'));
    }

    /**
     * Test the UrlBuilder::build method
     */
    public function testBuild()
    {
        $this->setProperty($this->instance, 'urlParts', ['test_value_1', 'test_value_2']);

        $this->assertSame('test_value_1/test_value_2', $this->instance->build());
    }

    /**
     * Test the UrlBuilder class constants
     */
    public function testConstants()
    {
        if (!defined(get_class($this->instance) . '::PATH_TYPE_REMOTE')) {
            $this->fail(sprintf('The %s constant have to be defined', 'PATH_TYPE_REMOTE'));
        }

        if (!defined(get_class($this->instance) . '::PATH_TYPE_UPLOAD')) {
            $this->fail(sprintf('The %s constant have to be defined', 'PATH_TYPE_UPLOAD'));
        }
    }
}

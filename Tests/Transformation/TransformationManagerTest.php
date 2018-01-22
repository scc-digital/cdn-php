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

use Scc\Cdn\Tests\Helper\Traits\ReflectionTrait;
use Scc\Cdn\Transformation\TransformationInterface;
use Scc\Cdn\Transformation\TransformationManager;
use Scc\Cdn\Transformation\TransformationPool;
use Scc\Cdn\Transformation\Type\Border;
use Scc\Cdn\Transformation\Type\Color;
use Scc\Cdn\Transformation\Type\Crop;
use Scc\Cdn\Transformation\Type\Gravity;
use Scc\Cdn\Transformation\Type\Width;

/**
 * Class TransformationManagerTest
 *
 * Test class for TransformationManager::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class TransformationManagerTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * @var TransformationManager
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->instance = new TransformationManager();
    }

    /**
     * Test the TransformationManager::__construct method
     */
    public function testConstruct()
    {
        $pool = $this->getProperty($this->instance, 'pool');
        $this->assertInstanceOf(TransformationPool::class, $pool);

        if (!$pool instanceof \Countable) {
            $this->fail(sprintf('The "%s" class must be an instance of %s', TransformationPool::class, \Countable::class));
        }

        $this->assertEmpty($pool);
    }

    /**
     * Test the TransformationManager::initPool method
     *
     * @depends testConstruct
     */
    public function testInitPool()
    {
        $pool = new TransformationPool();

        $this->setProperty($this->instance, 'pool', $pool);

        $this->assertSame($this->instance, $this->instance->initPool());

        $newPool = $this->getProperty($this->instance, 'pool');
        $this->assertNotSame($pool, $newPool);
        $this->assertInstanceOf(TransformationPool::class, $newPool);
        $this->assertEquals(0, count($newPool));
    }

    /**
     * Test the TransformationManager::getAttributes method
     *
     * @depends testConstruct
     */
    public function testGetAttributes()
    {
        $this->assertSame([], $this->getProperty($this->instance, 'attributes'));

        $this->setProperty($this->instance, 'attributes', ['test_value']);
        $this->assertSame(['test_value'], $this->instance->getAttributes());
    }

    /**
     * Provide transformations for mock
     *
     * @return array
     */
    public function getTransformations()
    {
        return [
            [[Width::class => 'width', Border::class => 'border'], ['test_key' => 'test_value'], ''],
            [[Width::class => 'width', Border::class => 'border', Color::class => 'color'], ['test_value'], ''],
            [[Width::class => 'width', Border::class => 'border', Gravity::class => 'gravity'], ['crop' => 'pad'], ''],
            [[Width::class => 'width', Border::class => 'border', Crop::class => 'crop'], ['crop' => 'pad'], 'crop_pad'],
            [[Width::class => 'width', Border::class => 'border', Crop::class => 'crop'], ['crop' => 'pad', 'width' => '50'], 'crop_pad,width_50'],
            [[Crop::class => 'crop', Width::class => 'width'], ['crop' => 'pad', 'width' => '50'], 'crop_pad,width_50']
        ];
    }

    /**
     * Test the TransformationManager::stringifyTransformations method
     *
     * @param array  $transformations
     * @param array  $options
     * @param string $expected
     *
     * @dataProvider getTransformations
     * @depends testConstruct
     */
    public function testStringifyTransformations(array $transformations, array $options, $expected)
    {
        $poolMock = $this->getMockBuilder(TransformationPool::class)
            ->getMock();

        $transformationMocks = [];
        foreach ($transformations as $transformationClass => $transformationName) {
            $transformationMock = $this->getMockBuilder($transformationClass)->getMock();

            $callCount = isset($options[$transformationName]) ? 2 : 1;

            $transformationMock->expects($this->exactly($callCount))
               ->method('getName')
               ->willReturn($transformationName);

            if ($callCount === 2) {
                $transformationMock->expects($this->once())
                   ->method('stringify')
                   ->willReturn(sprintf('%s_%s', $transformationName, $options[$transformationName]));
            }

            $transformationMocks[] = $transformationMock;
        }

        $this->setProperty($this->instance, 'pool', $poolMock);

        $poolMock->expects($this->once())
            ->method('getTransformations')
            ->willReturn($transformationMocks);

        $this->assertSame($expected, $this->instance->stringifyTransformations($options));
    }

    /**
     * Return the transformations to resolve
     *
     * @return array
     */
    public function getTransformationsToResolve()
    {
        return [
            ['image', ['lambda_key' => 'lambda_value', 'width' => 50], [Width::class], ['lambda_key' => 'lambda_value']],
            ['image', ['lambda_key' => 'lambda_value', 'crop' => 50], [Crop::class], ['lambda_key' => 'lambda_value']],
            ['exception_test', [], [], [], []]
        ];
    }

    /**
     * Test the TransformationManager::resolveTransformations method
     *
     * @param string $resourceType
     * @param array  $options
     * @param array  $expectedTransformations
     * @param array  $expectedAttrs
     *
     * @depends testInitPool
     *
     * @dataProvider getTransformationsToResolve
     */
    public function testResolveTransformations($resourceType, array $options, array $expectedTransformations, array $expectedAttrs)
    {
        if (!in_array($resourceType, ['image', 'file', 'video'])) {
            $this->expectException(\InvalidArgumentException::class);
            $this->instance->resolveTransformations($resourceType, $options);
            return;
        }

        $this->assertSame($this->instance, $this->instance->resolveTransformations($resourceType, $options));

        /** @var TransformationPool $pool */
        $pool = $this->getProperty($this->instance, 'pool');
        $this->assertInstanceOf(TransformationPool::class, $pool);

        $this->assertSame($expectedAttrs, $this->getProperty($this->instance, 'attributes'));

        foreach ($expectedTransformations as $transformationClass) {
            $class = new $transformationClass();
            $interface = 'Scc\\Cdn\\Transformation\\' . ucfirst($resourceType) . 'TypeInterface';

            $this->assertInstanceOf(TransformationInterface::class, $pool->getTransformationByName($class->getName()));
            $this->assertInstanceOf($interface, $pool->getTransformationByName($class->getName()));
        }
    }
}
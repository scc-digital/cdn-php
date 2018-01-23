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

use Scc\Cdn\Client;
use Scc\Cdn\Sign;
use Scc\Cdn\Tests\Helper\Traits\ReflectionTrait;
use Scc\Cdn\Transformation\TransformationManager;

/**
 * Class ClientTest
 *
 * Test class for Client::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * @var Client
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->instance = new Client('api_secret_value', 'my/base/url');
    }

    /**
     * Test the Client::__construct method
     */
    public function testConstruct()
    {
        $this->assertSame('api_secret_value', $this->getProperty($this->instance, 'apiSecret'));
        $this->assertSame('my/base/url', $this->getProperty($this->instance, 'baseUrl'));
        $this->assertInstanceOf(TransformationManager::class, $this->getProperty($this->instance, 'transformationManager'));
    }

    /**
     * Test the Client::getTaggedUrl method
     */
    public function testGetTaggedUrl()
    {
        foreach (['image' => 'img', 'video' => 'video'] as $key => $value) {
            $result = $this->instance->getTaggedUrl('test/path', ['resource_type' => $key]);

            $this->assertContains('<'.$value, $result);
            $this->assertContains(' src="', $result);
            $this->assertContains('my/base/url', $result);
            $this->assertContains('/test/path', $result);
            $this->assertContains('/>', $result);
        }

        $this->assertEmpty($this->instance->getTaggedUrl('test/path', ['resource_type' => 'file']));

        $this->expectException(\InvalidArgumentException::class);
        $this->instance->getTaggedUrl('test/path', ['resource_type' => 'test']);
    }

    /**
     * Provide data to test the class
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['image', 'test/path', 'upload', 'test_string'],
            ['image', 'test/path', 'upload', ''],
            ['image', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', ''],
            ['image', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', 'test_string'],
            ['video', 'test/path', 'upload', 'test_string'],
            ['video', 'test/path', 'upload', ''],
            ['video', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', ''],
            ['video', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', 'test_string'],
            ['file', 'test/path', 'upload', 'test_string'],
            ['file', 'test/path', 'upload', ''],
            ['file', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', ''],
            ['file', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', 'test_string'],
            ['test', 'test/path', 'upload', 'test_string'],
            ['test', 'test/path', 'upload', ''],
            ['test', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', ''],
            ['test', 'https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', 'test_string'],
            ['', '', '', ''],
        ];
    }

    /**
     * Test the Client::getUrl method
     *
     * @param string $resourceType
     * @param string $path
     * @param string $type
     * @param string $stringified
     *
     * @dataProvider dataProvider
     */
    public function testGetUrl($resourceType, $path, $type, $stringified)
    {
        if (empty($path)) {
            $result = $this->instance->getUrl($path, ['resource_type' => $resourceType]);

            $this->assertEmpty($result);
            return;
        }

        $mockTransformationManager = $this->getMockBuilder(TransformationManager::class)
            ->getMock();

        $mockTransformationManager->expects($this->once())
          ->method('resolveTransformations')
          ->with($resourceType, ['resource_type' => $resourceType])
          ->willReturn($mockTransformationManager);

        $mockTransformationManager->expects($this->once())
          ->method('stringifyTransformations')
          ->with(['resource_type' => $resourceType])
          ->willReturn($stringified);

        $this->setProperty($this->instance, 'transformationManager', $mockTransformationManager);

        $result = $this->instance->getUrl($path, ['resource_type' => $resourceType]);

        if (!empty($stringified)) {
            $sign = (new Sign('api_secret_value'))->generate($stringified, $path);

            $builtPath = $this->getReflectedMethod($this->instance, 'buildPath')
                ->invokeArgs($this->instance, [$path, $type]);

            $expectedPath = sprintf('%s/%s/%s/%s/%s/%s', 'my/base/url', $resourceType, $type, $sign, $stringified, $builtPath);
        } else {
            $expectedPath = $type === 'upload'
                ? sprintf('%s/%s', 'my/base/url', $path)
                : sprintf('%s', $path);
        }

        $this->assertNotEmpty($result);
        $this->assertEquals($expectedPath, $result);
    }

    /**
     * Test the Client::guessPathExtension method
     */
    public function testGuessPathExtension()
    {
        $path1 = 'https://cms.sccd.dev/assets/logo/EVRY2.png';

        $extension = $this->getReflectedMethod($this->instance, 'guessPathExtension')
            ->invokeArgs($this->instance, [$path1]);

        $this->assertEquals('png', $extension);
    }

    /**
     * Provide data to test the buildPath method
     *
     * @return array
     */
    public function buildPathDataProvider()
    {
        return [
            ['my/path', 'upload', 'my/path'],
            ['https://cms.sccd.dev/assets/logo/EVRY2.png', 'remote', 'aHR0cHM6Ly9jbXMuc2NjZC5kZXYvYXNzZXRzL2xvZ28vRVZSWTIucG5n.png']
        ];
    }

    /**
     * Test the Client::buildPath method
     * 
     * @param string $path
     * @param string $type
     * @param string $expectedResult
     *
     * @dataProvider buildPathDataProvider
     */
    public function testBuildPath($path, $type, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->getReflectedMethod($this->instance, 'buildPath')->invokeArgs($this->instance, [$path, $type])
        );
    }

    /**
     * Test the Client::getBaseUrl method
     */
    public function testGetBaseUrl()
    {
        $this->assertSame('my/base/url', $this->instance->getBaseUrl());
    }
}
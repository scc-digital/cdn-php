<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Decorator;

/**
 * Class HTMLDecoratorTest
 *
 * Test class for HTMLDecorator::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class HTMLDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockedInstance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {

    }

    /**
     * Test the HTMLDecorator::__construct method
     */
    public function testConstruct()
    {
        $instance = new HTMLDecorator('my/url');

        $reflectedClass = new \ReflectionClass($instance);
        $reflectedProperty = $reflectedClass->getProperty('url');
        $reflectedProperty->setAccessible('true');

        $this->assertSame('my/url', $reflectedProperty->getValue($instance));
    }

    /**
     * Test the HTMLDecorator::decorate method
     */
    public function testDecorate()
    {
        $options = ['test_attr' => 'test_value'];

        $mockedInstance = $this->getMockBuilder(HTMLDecorator::class)
             ->setConstructorArgs(['my/url'])
             ->setMethods(['getHTMLContent', 'getHTMLAttributes'])
             ->getMock();

        $mockedInstance->expects($this->once())
           ->method('getHTMLContent')
           ->with('')
           ->willReturn('%s lorem ipsum %s');

        $mockedInstance->expects($this->once())
           ->method('getHTMLAttributes')
           ->with($options)
           ->willReturn('test_attr="test_value"');

        $this->assertSame('my/url lorem ipsum test_attr="test_value"', $mockedInstance->decorate('', $options));
    }

    /**
     * Test the HTMLDecorator::getHTMLContent method
     *
     * @param string $resourceType
     *
     * @dataProvider getHtmlContentProvider
     */
    public function testGetHTMLContent($resourceType, $expectedReturn)
    {
        $instance = new HTMLDecorator('my/url');
        $reflectedInstance = new \ReflectionClass($instance);
        $method = $reflectedInstance->getMethod('getHTMLContent');
        $method->setAccessible(true);

        $result = $method->invokeArgs($instance, [$resourceType]);

        if (is_array($expectedReturn)) {
            foreach ($expectedReturn as $content) {
                $this->assertContains($content, $result);
            }
        } else {
            $this->assertSame($expectedReturn, $result);
        }
    }

    /**
     * Test the HTMLDecorator::getHTMLAttributes method
     *
     * @param array $options
     * @param string $expectedResult
     *
     * @dataProvider getHtmlAttributesProvider
     */
    public function testGetHTMLAttributes(array $options, $expectedResult)
    {
        $instance = new HTMLDecorator('my/url');
        $reflectedInstance = new \ReflectionClass($instance);
        $method = $reflectedInstance->getMethod('getHTMLAttributes');
        $method->setAccessible(true);

        $result = $method->invokeArgs($instance, [$options]);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * Provide useful data to test the Get HTML Attributes method
     *
     * @return array
     */
    public function getHtmlAttributesProvider()
    {
        return [
            [['class' => 'sample-css', 'data-toggle' => true], 'class="sample-css" data-toggle="1"'],
            [['class' => 'sample-css', 'data-toggle' => false], 'class="sample-css" data-toggle=""'],
            [['resource_type' => 'image'], ''],
            [['resource_type' => 'image', 'sign_url' => true, 'secure' => true], ''],
            [['resource_type' => 'image', 'class' => 'sample'], 'class="sample"'],
        ];
    }

    /**
     * Provide useful data to test the Get HTML Content method
     *
     * @return array
     */
    public function getHtmlContentProvider()
    {
        return [
            ['image', ['<img', 'src="%s"', ' %s ', '/>']],
            ['video', ['<video', 'src="%s"', ' %s ', '/>']],
            ['file', ''],
            ['thing', '']
        ];
    }
}
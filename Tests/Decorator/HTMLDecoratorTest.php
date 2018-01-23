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
use Scc\Cdn\Tests\Helper\Traits\ReflectionTrait;

/**
 * Class HTMLDecoratorTest
 *
 * Test class for HTMLDecorator::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class HTMLDecoratorTest extends \PHPUnit_Framework_TestCase
{
    use ReflectionTrait;

    /**
     * Test the HTMLDecorator::__construct method
     */
    public function testConstruct()
    {
        $this->assertSame('my/url', $this->getProperty(new HTMLDecorator('my/url'), 'url'));
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
        $method = $this->getReflectedMethod($instance, 'getHTMLContent');

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
        $method = $this->getReflectedMethod($instance, 'getHTMLAttributes');

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
            [['html_height' => '50', 'html_width' => '50'], 'height="50" width="50"'],
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
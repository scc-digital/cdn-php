<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Validator;

use Scc\Cdn\Validator\ResourceTypeValidator;

/**
 * Class ResourceTypeValidatorTest
 *
 * Test class for ResourceTypeValidator::class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class ResourceTypeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provide test data for resource types
     *
     * @return array
     */
    public function getResourceTypes()
    {
        return [
            ['', '', 'file'],
            ['', '', 'image'],
            ['', '', 'video'],
            [\InvalidArgumentException::class, 'The "pdf" resource type is not valid. Valid values are "image, video, file"', 'pdf'],
        ];
    }

    /**
     * Test the ResourceTypeValidator::validate method
     *
     * @param string $exceptionThrown
     * @param string $exceptionMessage
     * @param string $resourceType
     *
     * @dataProvider getResourceTypes
     */
    public function testValidate($exceptionThrown, $exceptionMessage, $resourceType)
    {
        if (!empty($exceptionThrown)) {
            $this->expectException($exceptionThrown);
            $this->expectExceptionMessage($exceptionMessage);
        }

        $this->assertNull(ResourceTypeValidator::validate($resourceType));
    }
}
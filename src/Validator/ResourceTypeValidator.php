<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Validator;

use Scc\Cdn\Transformation\Config\TransformationConfig;

/**
 * Class ResourceTypeValidator
 *
 * Valid if a resource type is authorized
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class ResourceTypeValidator implements ValidatorInterface
{
    /**
     * Validate the resource type
     *
     * @param string $value
     */
    public static function validate($value)
    {
        if (!isset(TransformationConfig::RESOURCES_TYPES[$value])) {
            throw new \InvalidArgumentException(sprintf(
                'The "%s" resource type is not valid. Valid values are "%s"',
                $value,
                implode(', ', TransformationConfig::RESOURCES_TYPES)
            ));
        }
    }
}
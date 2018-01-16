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

/**
 * Interface ValidatorInterface
 *
 * Implemented by the validators
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
interface ValidatorInterface
{
    /**
     * Validate the given value or throw an exception
     *
     * @param mixed $value
     */
    public static function validate($value);
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation\Type;

use Scc\Cdn\Transformation\ImageTypeInterface;
use Scc\Cdn\Transformation\VideoTypeInterface;

/**
 * Class FetchFormat
 *
 * The fetch_format transformation
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class FetchFormat extends AbstractType implements ImageTypeInterface, VideoTypeInterface
{
    const TRANSFORMATION_NAME = 'fetch_format';
}
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

/**
 * Class CoordX
 *
 * The x transformation
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class X extends AbstractType implements ImageTypeInterface
{
    const TRANSFORMATION_NAME = 'x';
}
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
 * Class Color
 *
 * The color transformation
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class Color extends AbstractType implements ImageTypeInterface
{
    const TRANSFORMATION_NAME = 'color';

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'co';
    }
}
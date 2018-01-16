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

/**
 * Class Border
 *
 * The border transformation
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class Border extends AbstractType
{
    const TRANSFORMATION_NAME = 'border';

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'bo';
    }
}
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

use Scc\Cdn\Transformation\Exception\UndefinedException;
use Scc\Cdn\Transformation\TransformationInterface;

/**
 * Class AbstractType
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
abstract class AbstractType implements TransformationInterface
{
    const KEY_VALUE_SEPARATOR = '_';

    /**
     * {@inheritdoc}
     */
    public function stringify($value)
    {
        if (is_scalar($value)) {
            return sprintf('%s%s%s', $this->getAlias(), static::KEY_VALUE_SEPARATOR, $value);
        }

        throw new \InvalidArgumentException('The given value to stringify is not a scalar one');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $currentType = get_class($this);

        if (defined($currentType . '::TRANSFORMATION_NAME')) {
            return constant($currentType . '::TRANSFORMATION_NAME');
        }

        throw new UndefinedException($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return strtolower(substr($this->getName(), 0, 1));
    }
}
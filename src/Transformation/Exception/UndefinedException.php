<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Transformation\Exception;

use Scc\Cdn\Transformation\TransformationInterface;

/**
 * Class UndefinedException
 *
 * Thrown when a transformation name is undefined
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class UndefinedException extends \LogicException
{
    /**
     * @var TransformationInterface
     */
    protected $transformation;

    /**
     * Build an instance of UndefinedException.
     *
     * @param TransformationInterface $transformation
     */
    public function __construct(TransformationInterface $transformation)
    {
        parent::__construct(sprintf(
            'The transformation have no name',
            $transformation->getName(),
            $transformation->getAlias()
        ));

        $this->transformation = $transformation;
    }

    /**
     * Get the transformation
     *
     * @return TransformationInterface
     */
    public function getTransformation()
    {
        return $this->transformation;
    }
}
<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Exception;

/**
 * Class MissingOptionException
 *
 * Thrown when an invalid option is given to the system
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class MissingOptionException extends \LogicException
{
    /**
     * The missing option
     *
     * @var string
     */
    protected $option;

    /**
     * Build an instance of MissingOptionException.
     *
     * @param string $option
     */
    public function __construct($option)
    {
        parent::__construct(sprintf('The option "%s" have to be set', $option));
        $this->option = $option;
    }

    /**
     * Get the option
     *
     * @return string
     */
    public function getOption()
    {
        return $this->option;
    }
}
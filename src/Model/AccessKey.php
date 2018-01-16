<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Scc\Cdn\Model;


class AccessKey
{

    /** @var string */
    protected $public;

    /** @var string */
    protected $private;

    /**
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param string $public
     * @return AccessKey
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param string $private
     * @return AccessKey
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        return $this;
    }


}
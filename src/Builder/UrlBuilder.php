<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Builder;

/**
 * Class UrlBuilder
 *
 * Build an url
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class UrlBuilder
{
    /**
     * The url base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * The parts of the url
     *
     * @var array
     */
    protected $urlParts = [];

    /**
     * Build an instance of UrlBuilder.
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Add a part to the url
     *
     * @param int    $position
     * @param string $urlPart
     *
     * @return $this
     */
    public function addUrlPart($position, $urlPart)
    {
        if (isset($this->urlPart[$position])) {
            throw new \RuntimeException(sprintf('The position %s is already attributed to an url part', $position));
        }

        $this->urlParts[$position] = $urlPart;

        return $this;
    }

    /**
     * Get the base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set the base path
     *
     * @param string $basePath
     *
     * @return UrlBuilder
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * Build the url
     *
     * @return string
     */
    public function build()
    {
        ksort($this->urlParts);

        $url = [];
        $url[] = $this->trimSlashes($this->basePath);
        foreach ($this->urlParts as $urlPart) {
            $url[] = $this->trimSlashes($urlPart);
        }

        return implode('/', $url);
    }

    protected function trimSlashes($value)
    {
        if (DIRECTORY_SEPARATOR === substr($value, -1)) {
            $value = substr($value, 0, -1);
        }

        if (DIRECTORY_SEPARATOR === substr($value, 0, 1)) {
            $value = substr($value, 0, 1);
        }

        return $value;
    }

    /**
     * Remove the last slash of the value
     *
     * @param string $value
     *
     * @return string
     */
    protected function removeLastSlash($value)
    {
        if (DIRECTORY_SEPARATOR === substr($value, -1)) {
            return substr($value, 0, -1);
        }

        return $value;
    }

    /**
     * Add a slash in the beginning of the value
     *
     * @param string $value
     *
     * @return string
     */
    protected function addFirstSlash($value)
    {
        if (DIRECTORY_SEPARATOR !== substr($value, 0, 1)) {
            return $value . DIRECTORY_SEPARATOR;
        }

        return $value;
    }
}
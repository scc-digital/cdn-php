<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn;

class Sign
{
    /**
     * The API secret
     *
     * @var string
     */
    private $apiSecret;

    /**
     * Build an instance of Sign.
     *
     * @param string $api_secret
     */
    public function __construct($api_secret)
    {
        $this->apiSecret = $api_secret;
    }

    /**
     * Generate the transformation
     *
     * @param string $transformation
     * @param string $source
     *
     * @return string
     */
    public function generate($transformation, $source)
    {
        $to_sign = implode('/', array_filter(array($transformation, $source)));
        $signature = sha1($to_sign . $this->apiSecret);
        return 's--' . substr($signature, 0, 8) . '--';
    }
}

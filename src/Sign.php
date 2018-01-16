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

    /** @var string */
    private $apiSecret;

    public function __construct($api_secret)
    {
        $this->apiSecret = $api_secret;
    }

    /**
     * @param array $transformation
     * @param string $source
     *
     * @return string
     */
    public function generate(array $transformation, $source)
    {

        $to_sign = implode('/', array_filter(array($transformation, $source)));
        $signature = str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode(sha1($to_sign . $this->apiSecret, TRUE)));
        return 's--' . substr($signature, 0, 8) . '--';
     

    }
}
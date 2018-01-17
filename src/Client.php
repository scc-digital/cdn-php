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
use Scc\Cdn\Builder\UrlBuilder;
use Scc\Cdn\Decorator\HTMLDecorator;
use Scc\Cdn\Exception\MissingOptionException;
use Scc\Cdn\Transformation\TransformationManager;

/**
 * Class Client
 *
 * The CDN client class
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
class Client
{
    /**
     * The media path
     *
     * @var string
     */
    protected $mediaPath;

    /**
     * The CDN secret api key
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * The CDN secret api key
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The url builder
     *
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * The transformation manager
     *
     * @var TransformationManager
     */
    protected $transformationManager;

    /**
     * Build an instance of Client.
     *
     * @param string $apiSecret
     * @param string $baseUrl
     */
    public function __construct($apiSecret, $baseUrl)
    {
        $this->apiSecret = $apiSecret;
        $this->urlBuilder = new UrlBuilder($baseUrl);
        $this->transformationManager = new TransformationManager();
    }

    /**
     * Get the resource url decorated into an HTML element
     *
     * Be careful, this method is deprecated and will be removed soon. Please use getUrl instead
     *
     * @param string $path
     * @param array  $options
     *
     * @return string
     *
     * @deprecated
     */
    public function getTaggedUrl($path, array $options)
    {
        return (new HTMLDecorator($this->getUrl($path, $options)))
            ->decorate($options['resource_type'], $this->transformationManager->getAttributes());
    }

    /**
     * Get the resource url
     *
     * @param string $path
     * @param array  $options
     *
     * @return string
     */
    public function getUrl($path, array $options)
    {
        $this->resolveOptions($options);

        $pathType = filter_var($path, FILTER_VALIDATE_URL) ? UrlBuilder::PATH_TYPE_REMOTE : UrlBuilder::PATH_TYPE_UPLOAD;

        if (empty($path)) {
            return '';
        }

        $transformations = $this->transformationManager
            ->resolveTransformations($options['resource_type'], $options)
            ->stringifyTransformations($options);

        $this->urlBuilder->addUrlPart(0, $this->urlBuilder->getBasePath());

        if (!empty($transformations)) {
            $this->urlBuilder
                ->addUrlPart(1, $options['resource_type'])
                ->addUrlPart(2, $pathType)
                ->addUrlPart(4, (new Sign($this->apiSecret))->generate($transformations, $path))
                ->addUrlPart(8, $transformations)
                ->addUrlPart(255, $this->buildPath($path, $pathType));
        } else {
            if ($pathType === UrlBuilder::PATH_TYPE_UPLOAD) {
                $this->urlBuilder->addUrlPart(255, $path);
            } else {
                $this->urlBuilder->replaceUrlPart(0, $path);
            }
        }

        return $this->urlBuilder->build();
    }

    /**
     * Guess the path extension based on the given path
     *
     * @param string $path
     *
     * @return string
     */
    protected function guessPathExtension($path)
    {
        $url = parse_url($path);
        $urlParts = explode('.', $url['path']);

        $extension = end($urlParts);

        if ($extension === $url['path']) {
            $contentType = explode('/', mime_content_type($path));
            $extension = $contentType[1];
        }

        return $extension;
    }

    /**
     * Build the path
     *
     * @param string $path
     * @param string $pathType
     *
     * @return string
     */
    protected function buildPath($path, $pathType)
    {
        if ($pathType === UrlBuilder::PATH_TYPE_REMOTE) {
            try {
                return base64_encode($path) . '.' . $this->guessPathExtension($path);
            } catch (\Exception $exception) {
                return base64_encode($path);
            }
        }

        return $path;
    }

    /**
     * Resolve the options
     *
     * @param array $options
     *
     * @throws MissingOptionException if a required option is missing
     */
    protected function resolveOptions(array &$options)
    {
        if (!isset($options['resource_type'])) {
            throw new MissingOptionException('resource_type');
        }
    }
}

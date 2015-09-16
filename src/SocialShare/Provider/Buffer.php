<?php

/*
 * This file is part of the SocialShare package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SocialShare\Provider;

/**
 * Buffer
 *
 * @author Mat Sumpter<mat@smptr.com>
 */
class Buffer implements ProviderInterface
{
    const NAME = 'buffer';
    const SHARE_URL = 'https://bufferapp.com/add?url=%s';
    const IFRAME_URL = 'https://widgets.bufferapp.com/button/?url=%s';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getLink($url, array $options = array())
    {
        return sprintf(self::SHARE_URL, urlencode($url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShares($url)
    {
        $html = file_get_contents(sprintf(self::IFRAME_URL, urlencode($url)));

        // Disable libxml errors
        $internalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument();
        $document->loadHTML($html);
        $aggregateCount = $document->getElementById('buffer_count');

        // Restore libxml errors
        libxml_use_internal_errors($internalErrors);

        return intval($aggregateCount->nodeValue);
    }
}

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
 * Pocket
 *
 * @author Mat Sumpter<mat@smptr.com> 
 */
class Pocket implements ProviderInterface
{
    const NAME = 'pocket';
    const SHARE_URL = 'https://getpocket.com/save?url=%s';
    const IFRAME_URL = 'https://widgets.getpocket.com/v1/button?count=vertical&url=%s';

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
        $aggregateCount = $document->getElementById('cnt');

        // Restore libxml errors
        libxml_use_internal_errors($internalErrors);

        return intval($aggregateCount->nodeValue);
    }
}

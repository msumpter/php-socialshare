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
 * Reddit
 *
 * @author Mat Sumpter<mat@smptr.com> 
 */
class Reddit implements ProviderInterface
{
    const NAME = 'reddit';
    const SHARE_URL = 'http://www.reddit.com/submit?url=%s';
    const API_URL = 'http://www.reddit.com/api/info.json?&url=%s';

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
        $options['url'] = $url;

        return sprintf(self::SHARE_URL, http_build_query($options, null, '&'));
    }

    /**
     * {@inheritDoc}
     */
    public function getShares($url)
    {
        $ups = 0; $downs = 0;

        $data = json_decode(file_get_contents(sprintf(self::API_URL, urlencode($url))));
        foreach($data->data->children as $child) {
            $ups+= (int) $child->data->ups;
            $downs+= (int) $child->data->downs;
        }

        return intval($count = $ups - $downs);
    }
}

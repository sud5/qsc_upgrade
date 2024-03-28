<?php
/**
 * This file is part of the league/oauth2-client library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Alex Bilbie <hello@alexbilbie.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link http://thephpleague.com/oauth2-client/ Documentation
 * @link https://packagist.org/packages/league/oauth2-client Packagist
 * @link https://github.com/thephpleague/oauth2-client GitHub
 */

namespace League\OAuth2\Client\Grant;

use League\OAuth2\Client\Token\AccessToken;

class Password implements GrantInterface
{
    public function __toString()
    {
        return 'password';
    }

    public function prepRequestParams($defaultParams, $params)
    {
        if (! isset($params['username']) || empty($params['username'])) {
            throw new \BadMethodCallException('Missing username');
        }

        if (! isset($params['password']) || empty($params['password'])) {
            throw new \BadMethodCallException('Missing password');
        }

        $params['grant_type'] = 'password';

        return array_merge($defaultParams, $params);
    }

    public function handleResponse($response = array())
    {
        return new AccessToken($response);
    }
}

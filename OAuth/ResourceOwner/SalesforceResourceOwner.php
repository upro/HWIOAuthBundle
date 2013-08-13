<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

/**
 * SalesforceResourceOwner
 *
 * @author Alexandre Deckner <adeckner@u-pro.fr>
 */
class SalesforceResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url' => 'https://login.salesforce.com/services/oauth2/authorize',
        'access_token_url'  => 'https://login.salesforce.com/services/oauth2/token',
        'infos_url'         => '/services/data/v28.0/chatter/users/me',
        'scope'             => 'full'
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier'      => 'id',
        'nickname'        => 'username',
        'realname'        => 'name'
    );

    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        $url = $accessToken['instance_url'] . $this->getOption('infos_url');

        $content = $this->doGetUserInformationRequest($url, array('access_token' => $accessToken['access_token']))->getContent();

        $response = $this->getUserResponse();
        $response->setResponse($content);
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    protected function doGetUserInformationRequest($url, array $parameters = array())
    {
        return $this->httpRequest($url, null, array('Authorization: OAuth ' . $parameters['access_token']));
    }
}

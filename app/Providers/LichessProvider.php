<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;

class LichessProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'preference:read',
        'preference:write',
        'email:read',
        'challenge:read',
        'challenge:write',
        'tournament:write',
        'board:play'
    ];v

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oauth.lichess.org/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://oauth.lichess.org/oauth';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://lichess.org/api/account';

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        $user = json_decode($response->getBody(), true);

        if (in_array('user:email', $this->scopes)) {
            $user['email'] = $this->getEmailByToken($token);
        }

        return $user;
    }

    /**
     * Get the email for the given access token.
     *
     * @param  string  $token
     * @return string|null
     */
    protected function getEmailByToken($token)
    {
        $emailsUrl = 'https://lichess.org/api/account/email';

        try {
            $response = $this->getHttpClient()->get(
                $emailsUrl, $this->getRequestOptions($token)
            );
        } catch (Exception $e) {
            return;
        }

        foreach (json_decode($response->getBody(), true) as $email) {
            if ($email['primary'] && $email['verified']) {
                return $email['email'];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            // 'nickname' => $user['login'],
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            // 'avatar' => $user['avatar_url'],
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @param string $token
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => 'token '.$token,
            ],
        ];
    }
}

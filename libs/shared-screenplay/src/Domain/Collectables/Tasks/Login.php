<?php
namespace SharedScreenplay\Domain\Collectables\Tasks;

use SharedScreenplay\Core\Actor;
use SharedScreenplay\Core\Task;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpRequest;

class Login implements Task
{
    /**
     * Attempt a login for a user against the Collectables app.
     *
     * Example:
     * ```php
     * $actor->attempts(new Login('demo', 'password', 'http://localhost:8000'));
     * ```
     */
    public function __construct(
        private string $username,
        private string $password,
        private string $baseUrl = 'http://127.0.0.1:8000'
    ) {
    }

    /**
     * Send the login form and return the response body.
     */
    public function performAs(Actor $actor): string
    {
        /** @var HttpAbility $http */
        $http = $actor->ability(HttpAbility::class);
        $body = http_build_query([
            'login' => [$this->username, $this->password],
        ]);
        $request = new HttpRequest(
            'POST',
            rtrim($this->baseUrl, '/') . '/validate-user.php',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $body
        );

        $response = $http->client->request($request);
        return $response->body;
    }
}

<?php
namespace SharedScreenplay\Domain\Collectables\Tasks;

use SharedScreenplay\Core\Actor;
use SharedScreenplay\Core\Task;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpRequest;

class Checkout implements Task
{
    /**
     * Finalize the cart and navigate to the order processed page.
     *
     * Example:
     * ```php
     * $actor->attempts(new Checkout('http://localhost:8000'));
     * ```
     */
    public function __construct(private string $baseUrl = 'http://127.0.0.1:8000')
    {
    }

    /**
     * Request the checkout confirmation page.
     */
    public function performAs(Actor $actor): string
    {
        /** @var HttpAbility $http */
        $http = $actor->ability(HttpAbility::class);
        $request = new HttpRequest('GET', rtrim($this->baseUrl, '/') . '/order-processed.php');
        $response = $http->client->request($request);
        return $response->body;
    }
}

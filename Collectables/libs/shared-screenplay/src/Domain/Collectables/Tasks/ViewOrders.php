<?php
namespace SharedScreenplay\Domain\Collectables\Tasks;

use SharedScreenplay\Core\Actor;
use SharedScreenplay\Core\Task;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpRequest;

class ViewOrders implements Task
{
    /**
     * Retrieve the orders listing page.
     *
     * Example:
     * ```php
     * $html = $actor->attempts(new ViewOrders('http://localhost:8000'));
     * ```
     */
    public function __construct(private string $baseUrl = 'http://127.0.0.1:8000')
    {
    }

    /**
     * Request the orders page and return the body.
     */
    public function performAs(Actor $actor): string
    {
        /** @var HttpAbility $http */
        $http = $actor->ability(HttpAbility::class);
        $request = new HttpRequest('GET', rtrim($this->baseUrl, '/') . '/view-orders.php');
        $response = $http->client->request($request);
        return $response->body;
    }
}

<?php
namespace SharedScreenplay\Domain\Collectables\Tasks;

use SharedScreenplay\Core\Actor;
use SharedScreenplay\Core\Task;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpRequest;

class ViewStore implements Task
{
    /**
     * Load the products page for a specific store.
     *
     * Example:
     * ```php
     * $actor->attempts(new ViewStore('1', 'http://localhost:8000'));
     * ```
     */
    public function __construct(
        private string $storeId,
        private string $baseUrl = 'http://127.0.0.1:8000'
    ) {
    }

    /**
     * Post the store selection form and return the HTML body.
     */
    public function performAs(Actor $actor): string
    {
        /** @var HttpAbility $http */
        $http = $actor->ability(HttpAbility::class);
        $body = http_build_query([
            'storeID' => [$this->storeId => ''],
        ]);
        $request = new HttpRequest(
            'POST',
            rtrim($this->baseUrl, '/') . '/products.php',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $body
        );
        $response = $http->client->request($request);
        return $response->body;
    }
}

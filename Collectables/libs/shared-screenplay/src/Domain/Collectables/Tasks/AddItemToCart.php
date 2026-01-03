<?php
namespace SharedScreenplay\Domain\Collectables\Tasks;

use SharedScreenplay\Core\Actor;
use SharedScreenplay\Core\Task;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpRequest;

class AddItemToCart implements Task
{
    /**
     * Add a product to the cart using the token returned from the app.
     *
     * Example:
     * ```php
     * $actor->attempts(new AddItemToCart(5, 'token-123', 'http://localhost:8000'));
     * ```
     */
    public function __construct(
        private int $productId,
        private string $token,
        private string $baseUrl = 'http://127.0.0.1:8000'
    ) {
    }

    /**
     * Execute the GET request to add the item and return the HTML body.
     */
    public function performAs(Actor $actor): string
    {
        /** @var HttpAbility $http */
        $http = $actor->ability(HttpAbility::class);
        $query = http_build_query([
            'ItemToAdd' => $this->productId,
            'tokenID' => $this->token,
        ]);
        $request = new HttpRequest(
            'GET',
            rtrim($this->baseUrl, '/') . '/products.php?' . $query
        );
        $response = $http->client->request($request);
        return $response->body;
    }
}

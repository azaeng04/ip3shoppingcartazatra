<?php
use PHPUnit\Framework\TestCase;
use SharedScreenplay\Core\Actor;
use SharedScreenplay\Http\FakeHttpClient;
use SharedScreenplay\Http\HttpAbility;
use SharedScreenplay\Http\HttpResponse;
use SharedScreenplay\Domain\Collectables\Tasks\Login;
use SharedScreenplay\Domain\Collectables\Tasks\ViewStore;
use SharedScreenplay\Domain\Collectables\Tasks\AddItemToCart;
use SharedScreenplay\Domain\Collectables\Tasks\Checkout;
use SharedScreenplay\Domain\Collectables\Tasks\ViewOrders;

final class ScreenplayFlowTest extends TestCase
{
    public function test_critical_checkout_flow_happy_path(): void
    {
        $fake = new FakeHttpClient([
            new HttpResponse(302, [], 'logged-in'),
            new HttpResponse(200, [], 'store-products'),
            new HttpResponse(200, [], 'item-added'),
            new HttpResponse(200, [], 'order-processed'),
            new HttpResponse(200, [], 'orders-list'),
        ]);

        $actor = (new Actor('Customer'))->can(new HttpAbility($fake));

        $loginBody = $actor->attempts(new Login('customer1', 'customer1'));
        $this->assertSame('logged-in', $loginBody);

        $storeBody = $actor->attempts(new ViewStore('Cars'));
        $this->assertSame('store-products', $storeBody);

        $addBody = $actor->attempts(new AddItemToCart(21, 'token-123'));
        $this->assertSame('item-added', $addBody);

        $checkoutBody = $actor->attempts(new Checkout());
        $this->assertSame('order-processed', $checkoutBody);

        $ordersBody = $actor->attempts(new ViewOrders());
        $this->assertSame('orders-list', $ordersBody);

        $requests = $fake->requests();
        $this->assertCount(5, $requests, 'Expected each critical step to issue a request');
        $this->assertSame('POST', $requests[0]->method);
        $this->assertStringContainsString('/validate-user.php', $requests[0]->url);
        $this->assertStringContainsString('/products.php', $requests[1]->url);
        $this->assertStringContainsString('ItemToAdd=21', $requests[2]->url);
        $this->assertStringContainsString('/order-processed.php', $requests[3]->url);
        $this->assertStringContainsString('/view-orders.php', $requests[4]->url);
    }
}

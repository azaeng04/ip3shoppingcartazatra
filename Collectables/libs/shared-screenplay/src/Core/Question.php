<?php
namespace SharedScreenplay\Core;

interface Question
{
    /**
     * Query state on behalf of an actor.
     *
     * Example in a test:
     * ```php
     * $orders = $actor->asks(new ViewOrders());
     * $this->assertNotEmpty($orders->json());
     * ```
     */
    public function askAs(Actor $actor): mixed;
}

<?php
namespace SharedScreenplay\Http;

use SharedScreenplay\Core\Ability;

class HttpAbility implements Ability
{
    /**
     * Wrap any HttpClient so actors can make HTTP calls.
     *
     * Example:
     * ```php
     * $actor->can(new HttpAbility(new FakeHttpClient()));
     * ```
     */
    public function __construct(public readonly HttpClient $client)
    {
    }
}

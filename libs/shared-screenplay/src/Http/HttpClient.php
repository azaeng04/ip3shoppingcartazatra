<?php
namespace SharedScreenplay\Http;

interface HttpClient
{
    /**
     * Send an HTTP request and return a response.
     *
     * Example with Fake client in a test:
     * ```php
     * $client = new FakeHttpClient();
     * $client->when('/login.php', new HttpResponse(200, [], 'ok'));
     * $response = $client->request(new HttpRequest('GET', '/login.php'));
     * ```
     */
    public function request(HttpRequest $request): HttpResponse;
}

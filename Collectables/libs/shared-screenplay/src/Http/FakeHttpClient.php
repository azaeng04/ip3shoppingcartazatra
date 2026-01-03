<?php
namespace SharedScreenplay\Http;

use RuntimeException;

class FakeHttpClient implements HttpClient
{
    /** @var HttpRequest[] */
    private array $requests = [];
    /** @var HttpResponse[] */
    private array $responses;

    /**
     * @param HttpResponse[] $responses
     *
     * Example:
     * ```php
     * $client = new FakeHttpClient([
     *     new HttpResponse(200, [], 'ok'),
     * ]);
     * $response = $client->request(new HttpRequest('GET', '/health'));
     * $this->assertSame('ok', $response->body);
     * ```
     */
    public function __construct(array $responses = [])
    {
        $this->responses = $responses;
    }

    /**
     * Append a canned response to return on the next request.
     */
    public function pushResponse(HttpResponse $response): void
    {
        $this->responses[] = $response;
    }

    /** @return HttpRequest[] */
    public function requests(): array
    {
        return $this->requests;
    }

    /**
     * Record the request and return the next queued fake response.
     *
     * Example inside a test flow:
     * ```php
     * $client->pushResponse(new HttpResponse(302, [], 'redirect'));
     * $client->request(new HttpRequest('POST', '/login'));
     * $this->assertCount(1, $client->requests());
     * ```
     */
    public function request(HttpRequest $request): HttpResponse
    {
        $this->requests[] = $request;
        if (count($this->responses) === 0) {
            throw new RuntimeException('No fake responses left to return.');
        }
        return array_shift($this->responses);
    }
}

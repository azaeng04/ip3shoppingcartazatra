<?php
namespace SharedScreenplay\Http;

class HttpResponse
{
    /**
     * Immutable HTTP response holder used by Screenplay questions.
     *
     * Example:
     * ```php
     * $response = new HttpResponse(200, ['Content-Type' => 'application/json'], '{"ok":true}');
     * $this->assertSame(200, $response->status);
     * ```
     */
    public function __construct(
        public readonly int $status,
        /** @var array<string,string> */
        public readonly array $headers,
        public readonly string $body
    ) {
    }
}

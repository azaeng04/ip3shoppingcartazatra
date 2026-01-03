<?php
namespace SharedScreenplay\Http;

class HttpRequest
{
    /**
     * Immutable request DTO used by HTTP abilities.
     *
     * Example in a test:
     * ```php
     * $request = new HttpRequest('POST', 'http://localhost/login.php', ['Content-Type' => 'application/x-www-form-urlencoded'], 'username=user&password=pass');
     * $this->assertSame('POST', $request->method);
     * ```
     */
    public function __construct(
        public readonly string $method,
        public readonly string $url,
        /** @var array<string,string> */
        public readonly array $headers = [],
        public readonly ?string $body = null
    ) {
    }
}

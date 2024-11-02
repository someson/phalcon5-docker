<?php

namespace App\Modules\Api\Components;

use App\Modules\Api\Exceptions\HttpClientException;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Http\Request;

class Bearer implements ApiAuthInterface
{
    private ?string $token = null;

    /**
     * @throws HttpClientException
     */
    public function from(Request $request): static
    {
        $authKey = 'Authorization'; // Authorization: Bearer xjhgjhgkjdgfjdhgfjdgfjdg
        $authHeader = $request->hasHeader($authKey) ? trim($request->getHeader($authKey)) : null;
        if (! $authHeader) {
            throw new HttpClientException(StatusCode::STATUS_UNAUTHORIZED);
        }
        [$tokenType, $this->token] = explode(' ', $authHeader);
        if (strtolower($tokenType) !== 'bearer') {
            throw new HttpClientException(StatusCode::STATUS_UNAUTHORIZED);
        }
    }

    public function getToken(): string
    {
        return $this->token;
    }
}

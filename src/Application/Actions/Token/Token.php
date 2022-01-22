<?php

declare(strict_types=1);

namespace App\Application\Actions\Token;

use DateTime;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class Token
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $requested_scopes = $request->getParsedBody() ?: [];

        $valid_scopes = [
            "post.create",
            "post.read",
            "post.update",
            "post.delete",
            "post.list",
            "post.all"
        ];

        $scopes = array_filter($requested_scopes, function ($needle) use ($valid_scopes) {
            return in_array($needle, $valid_scopes);
        });

        $now = new DateTime();
        $future = new DateTime("now +2 hours");
        $server = $request->getServerParams();

        $jti = Uuid::uuid4();

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $server["PHP_AUTH_USER"],
            "scope" => $scopes
        ];

        $secret = getenv("JWT_SECRET");
        $token = JWT::encode($payload, $secret);

        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}
<?php
// [LDAP / OAUTH] Génération token Passport
namespace App\Models\Passport;

use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;

class Authenticator
{
    use HandlesOAuthErrors;

    private $server;
    private $request = null;

    public function __construct(ServerRequestInterface $request)
    {
        $this->server = resolve(AuthorizationServer::class);
        $this->request = $request;
    }

    public function authenticate(PassportClient $client, $username, $password)
    {
        $request = $this->request->withParsedBody([
            "username" => $username,
            "password" => $password,
            "client_id" => $client->id,
            "client_secret" => $client->secret,
            "grant_type" => "password",
            "scope" => "*"
        ]);

        $response = $this->withErrorHandling(function () use ($request) {
            return $this->convertResponse($this->server->respondToAccessTokenRequest($request, new Psr7Response));
        })->content();

        return json_decode($response);
    }
}

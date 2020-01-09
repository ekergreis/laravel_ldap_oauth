<?php
// [LDAP / OAUTH] Controller Login : Vérification LDAP / Génération token
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Passport\Authenticator as PassportAuthenticator;
use App\Models\Passport\PassportClient;
use Illuminate\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function login(ServerRequestInterface $request): JsonResponse
    {
        // [LDAP / OAUTH] Récupération et vérification datas
        $dataAuth = json_decode($request->getBody()->getContents());
        if(empty($dataAuth->username) || empty($dataAuth->password) || empty($dataAuth->apiKey))
            return response()->json(["error" => "Données manquantes"], 401);

        // [LDAP / OAUTH] Vérification et Récupération passport client informations à partir de la clé passée
        $client = PassportClient::findClientBySecret($dataAuth->apiKey);
        if(empty($client->secret))
            return response()->json(["error" => "Client refusé"], 401);

        // [LDAP / OAUTH] Tentative de connexion avec le provider ldap
        if (!Auth::attempt(['username' => $dataAuth->username, 'password' => $dataAuth->password], true))
            return response()->json(["error" => "Connexion refusée"], 401);

        // [LDAP / OAUTH] Génération passport token OAuth
        $passport = (new PassportAuthenticator($request))
            ->authenticate($client, $dataAuth->username, $dataAuth->password);

        // [LDAP / OAUTH] Succes envoi des informations
        return response()->json([
            "access_token" => $passport->access_token,
            "expires_in" => $passport->expires_in,
            "refresh_token" => $passport->refresh_token,
        ], 200);
    }
}

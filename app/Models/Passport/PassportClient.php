<?php
// [LDAP / OAUTH] Récupération des infos Passport à partir d'une api key
namespace App\Models\Passport;

use Laravel\Passport\Client;

class PassportClient extends Client
{
    public static function findClientBySecret($clientSecret): PassportClient
    {
        $passportKey = static::where('secret', $clientSecret)->get()->first();
        if(empty($passportKey))  $passportKey = new PassportClient();
        return $passportKey;
    }
}

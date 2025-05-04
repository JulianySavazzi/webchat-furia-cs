<?php

namespace App\Integrations;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PandaScore
{
    /**
     * @var PendingRequest
     */
    protected $httpClient;

    /**
     * @var mixed
     */
    protected $baseUrl;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
        ]);
        $this->baseUrl = env('PANDA_BASE_URL');
    }

    /**
     * informar o path, exemplo: /matches, /matches/running, /tournaments, /tournaments/upcoming
     * pode informar o path com filtros tambem: /matches?filter%5Bopponent_id%5D=124530
     *
     * @param string $path
     * @return array|mixed
     */
    public function makeGetRequest(string $path)
    {
        $accessToken = env('PANDA_API_KEY');
        try{
            $response = $this->httpClient->withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($this->baseUrl . $path);

            return $response->json();
        } catch(\Exception $e){
            throw new ConnectionException("Erro ao se conectar com a API: " . $e->getMessage());
        }
    }
}

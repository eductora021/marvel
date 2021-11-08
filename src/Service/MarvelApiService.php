<?php

namespace App\Service;
use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class MarvelApiService
{
    private $client;
    private $publicKey;
    private $privateKey;
    private $apiBaseUrl;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->publicKey = "ae40493ad52546b47f624f1fe1802908";
        $this->privateKey = "e7776835ce73d4689f76d4667ad1487a0249f19f";
        $this->charactersApiBaseUrl = "http://gateway.marvel.com/v1/public/characters";
    }

     /*Recupération des personnages à partir d'un nombre */
    public function getMarvels(int $from = 20, int $to = 100) : array 
    {
        $date = new DateTime();
        $currentTimestamp = $date->getTimestamp();
        $hash = $this->getApiHash($currentTimestamp, $this->publicKey,  $this->privateKey);

        $response = $this->client->request(
            'GET',
            "{$this->charactersApiBaseUrl}?ts={$currentTimestamp}&apikey={$this->publicKey}&hash={$hash}&offset={$from}&limit={$to}" 
        );
        if($response->getStatusCode() != 200){
            return [];
        }
        return $response->toArray()['data']['results'];
    }

    /*Recupération d'un personnage via son ID */
    public function getCharactersById(int $id){
        $date = new DateTime();
        $currentTimestamp = $date->getTimestamp();
        $hash = $this->getApiHash($currentTimestamp, $this->publicKey,  $this->privateKey);

        $response = $this->client->request(
            'GET',
            "{$this->charactersApiBaseUrl}/{$id}?ts={$currentTimestamp}&apikey={$this->publicKey}&hash={$hash}" 
        );

        if($response->getStatusCode() != 200){
            return false;
        }
        return $response->toArray()['data']['results'][0];
    }

    private function getApiHash(int $currentTimestamp, $publicKey, $privateKey) : string {
        return md5($currentTimestamp.$privateKey.$publicKey);
    }
    
}
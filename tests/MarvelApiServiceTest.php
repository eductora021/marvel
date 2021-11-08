<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\MarvelApiService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MarvelApiServiceTest extends KernelTestCase
{
    
    protected MarvelApiService $marvelApiService;

    protected function setUp() : void
    {
        static::bootKernel(); // récupération du container qui contient toutes les injections de dépendance
        $client = static::getContainer()->get(HttpClientInterface::class);
        $this->marvelApiService = new MarvelApiService($client);
    }

     /*Test pour voir si l'api retourne 20 élément à partir du 20e*/
    public function testApi(){
        
        $marvels = $this->marvelApiService->getMarvels(20, 20);
        $this->assertCount(
            20,
            $marvels, 
        );

    }
}
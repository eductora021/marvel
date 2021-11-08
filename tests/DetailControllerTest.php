<?php

use App\Service\MarvelApiService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class DetailControllerTest extends WebTestCase
{
    /*Test pour voir si la page contient les informations demandées*/
    public function testDetailPage()
    {
        $client = static::createClient();
        $crawler = $client->request('get', '/detail/1011176') ;
        $title = $crawler->filter('.card-title')->first()->text();
        $this->assertEquals('Ajak', $title);
    }
}
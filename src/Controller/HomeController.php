<?php

namespace App\Controller;

use App\Service\MarvelApiService;
use App\Entity\Character;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    private MarvelApiService $marvelApiService;
    /**
     * @Route("/home", name="home")
     */
    public function index(MarvelApiService $marvelApiService): Response
    {
    
        $this->marvelApiService = $marvelApiService;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'characters' => $this->getCharacters()
        ]);
    }

    private function getCharacters(int $from = 20, int $to = 100) : array{
    
        $charactersApi = $this->marvelApiService->getMarvels($from, $to);
        $characters = array();
        foreach($charactersApi as $character){
            $characters[] = new Character(
                $character['id'],
                $character['name'],
                $character['thumbnail']['path'].'/portrait_incredible.'.$character['thumbnail']['extension']
            );
        }
        return $characters;
    }
}


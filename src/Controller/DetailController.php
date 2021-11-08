<?php

namespace App\Controller;
use App\Service\MarvelApiService;
use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends AbstractController
{

    private MarvelApiService $marvelApiService;
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function index(int $id = 0, MarvelApiService $marvelApiService): Response
    {
        $this->marvelApiService = $marvelApiService;
        if(is_bool($apiCharacter = $this->marvelApiService->getCharactersById($id)) ){
            return $this->redirectToRoute('home');
        }
        $charcater = $this->getCharacter($apiCharacter);

        return $this->render('detail/index.html.twig', [
            'controller_name' => 'DetailController',
            'character_detail' => $charcater
        ]);
    }

    private function getCharacter(array $apiCharacter) : Character{
        $comics = array();
        foreach($apiCharacter['comics']['items'] as $comic){
            $comics[] = $comic['name'];
        }
        $charcater = new Character(
            $apiCharacter['id'],
            $apiCharacter['name'],
            $apiCharacter['thumbnail']['path'].'/portrait_incredible.'.$apiCharacter['thumbnail']['extension'],
            $apiCharacter['description'],
        );
        $charcater->setComics($comics);
        return $charcater;
    }

    
}



<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\MarvelApiService;
use App\Entity\Character;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
class FavorisController extends AbstractController
{
    
    private RequestStack $requestStack;
    private MarvelApiService $marvelApiService;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    
    /**
     * @Route("/favoris", name="favoris")
     */
    public function index(MarvelApiService $marvelApiService, Request $request): Response
    {   $this->marvelApiService = $marvelApiService;
        $session = $this->requestStack->getSession();
        $favsArray = $session->get('favs');
        if(!isset($favsArray)){
            $session->set('favs', array());
        }
        $charcaters = $this->getCharacters($request);
        return $this->render('favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
            'characters' => $charcaters
        ]);
    }

    /**
     * @Route("/addFavoris", name="addFavoris")
     */
    public function addFavoris(Request $request): Response{
        $arrData = array();
        if($id = $request->request->get('id')){
            $session = $this->requestStack->getSession();
            $favsArray = $session->get('favs');

            if(count($favsArray) == 5){
                $arrData = ['message' => "Nombre maximal : 5"];    
            }
            elseif(in_array($id, $favsArray)){
                $arrData = ['message' => "Ce personnage est déjà dans vos favoris"];    
            }
            else {
                $favsArray[] = $id;
                $session->set('favs', $favsArray);
                $arrData = ['statut' => "ok"];    
            }
        }
        $response = new JsonResponse($arrData);
        return $response;
    }

     /**
     * @Route("/deleteFav/{id}", name="deleteFav")
     */
    public function deleteFav(int $id, Request $request): Response{
        
            $session = $this->requestStack->getSession();
            $favsArray = $session->get('favs');

            if(in_array($id, $favsArray)){
                $favsArray = array_diff($favsArray, array($id));
            }
            $session->set('favs', $favsArray);
        
            return $this->redirectToRoute('favoris');
    }

    private function getCharacters(Request $request) : array{
        $session = $this->requestStack->getSession();
        $favsArray = $session->get('favs');
        $charcaters = array();
        foreach($favsArray as $favoris){
            $apiCharacter = $this->marvelApiService->getCharactersById($favoris);
        
            $charcater = new Character(
                $apiCharacter['id'],
                $apiCharacter['name'],
                $apiCharacter['thumbnail']['path'].'/portrait_incredible.'.$apiCharacter['thumbnail']['extension']
            );

            $charcaters[] = $charcater;
        }
        
        return $charcaters;
    }
    
    
}

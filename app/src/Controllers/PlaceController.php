<?php

namespace App\Controllers;

use App\Factories\BasicFactory;
use App\Factories\MessageFactory;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\Place;
use App\Models\Photo;

final class PlaceController
{
    private $view;
    private $logger;
	private $user;

    public function __construct($view, LoggerInterface $logger, $user)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->model = $user;
    }

    /**
     * Method which display a kebab place from its id 
     * args - {id: id of the place}
     */
    public function displayPlace(Request $request, Response $response, $args) {
        $this->logger->info('Start to display kebab place with id: '.$args['id']);
        $place = Place::find($args['id']); //Look for the place in DB
        
        if($place != null) {
            //place found
            $this->logger->info('Place '.$place->id.' found: display profile');
            
            $menuActive = 4;    
            
            //Preparation of datas to send to the twig
            $datas = BasicFactory::make($menuActive);
            $datas['place'] = $place;
            $datas['posts'] = $place->photos()->with('user', 'place', 'notes')->get()->sortByDesc('id');

            return $this->view->render($response, 'place.twig', $datas);
        } else {
            //place not found
            $this->logger->info('Error: place '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('Le restaurant recherché n\'existe pas ou plus.');

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
    
    /**
     * Method which display all kebab places
     */
    public function displayAllPlaces(Request $request, Response $response, $args) {
        $this->logger->info('Start to display all kebab place');
        $places = Place::with('photos')->get();
        
        if($places != null) {
            //some places found
            $this->logger->info('Places found: display places');
            
            $menuActive = 4;    
            
            //Preparation of datas to send to the twig
            $datas = BasicFactory::make($menuActive);
            $datas['places'] = $places;

            return $this->view->render($response, 'allPlaces.twig', $datas);
        } else {
            //no places found
            $this->logger->info('Error: no places found in database');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('Aucun restaurant n\'a été trouvé. Veuillez réessayer plus tard.');

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
}
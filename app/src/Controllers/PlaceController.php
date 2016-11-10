<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\Place;

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
     * args - {id: user's id}
     */
    public function displayPlace(Request $request, Response $response, $args) {
        $this->logger->info('Start to display kebab place with id: '.$args['id']);
        $place = Place::find($args['id']); //Look for the place in DB
        
        if($place != null) {
            //place found
            $this->logger->info('Place '.$place->id.' found: display profile');
            
            $menuActive = 4;    
            
            //Preparation of datas to send to the twig
            $datas = array(
                'place' => $place, 
                'posts' => $place->photos()->with('user', 'place', 'notes')->get()->sortByDesc('id'),
                'menuActive' => $menuActive,
                'user' => Sentinel::forceCheck());
            
            $this->view->render($response, 'place.twig', $datas);    
        } else {
            //place not found
            $this->logger->info('Error: place '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = array(
                'success' => false, 
                'message' => 'Le restaurant recherché n\'existe pas ou plus.', 
                'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
            
            $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
}
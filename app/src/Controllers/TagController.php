<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\Tags;
use App\Models\Photo;

final class TagController
{
    const NUMBER_OF_TAGS_TO_DISPLAY = 10;
    
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
     * Method which display last tags
     */
    public function displayLastTags(Request $request, Response $response, $args) {
        $this->logger->info('Start to display last tags');
        $tags = Tags::all()->sortByDesc('id')->take(TagController::NUMBER_OF_TAGS_TO_DISPLAY);
        
        if($tags != null) {
            //some tags found
            $this->logger->info('Some tags found: display them');
            
            $menuActive = 5;    
            
            //Preparation of datas to send to the twig
            $datas = array(
                'tags' => $tags, 
                'menuActive' => $menuActive,
                'user' => Sentinel::forceCheck());
            
            $this->view->render($response, 'lastTags.twig', $datas);    
        } else {
            //no tags found
            $this->logger->info('Error: no tags found in database');
            
            //Preparation of datas to send to the twig
            $datas = array(
                'success' => false, 
                'message' => 'Aucun tag n\'a été trouvé. Veuillez réessayer plus tard.', 
                'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
            
            $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
}
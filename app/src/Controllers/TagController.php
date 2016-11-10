<?php

namespace App\Controllers;

use App\Factories\BasicFactory;
use App\Factories\MessageFactory;
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
     * Method which display a tag from its id 
     * args - {id: id of the tag}
     */
    public function displayTag(Request $request, Response $response, $args) {
        $this->logger->info('Start to display tag with id: '.$args['id']);
        $tag = Tags::find($args['id']); //Look for the tag in DB
        
        if($tag != null) {
            //tag found
            $this->logger->info('Tag '.$tag->id.' found: display tag');
            
            $menuActive = 5;    
            
            //Preparation of datas to send to the twig
            $datas = BasicFactory::make($menuActive);
            $datas['tag'] = $tag;
            $datas['posts'] = $tag->photos()->with('user', 'place', 'notes')->get()->sortByDesc('id');

            $this->view->render($response, 'tag.twig', $datas);    
        } else {
            //tag not found
            $this->logger->info('Error: tag '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('Le tag recherché n\'existe pas ou plus.');

            $this->view->render($response, 'displayMessage.twig', $datas);
        }
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
            $datas = BasicFactory::make($menuActive);
            $datas['tags'] = $tags;

            
            $this->view->render($response, 'lastTags.twig', $datas);    
        } else {
            //no tags found
            $this->logger->info('Error: no tags found in database');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('Aucun tag n\'a été trouvé. Veuillez réessayer plus tard.');

            $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
}
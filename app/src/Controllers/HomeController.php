<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\Photo;
use App\Models\Place;
use App\Models\Tags;
use App\Models\User;

final class HomeController
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
     * Method which renders the home page
     */
    public function displayHomepage(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        
        //Preparation of datas to send to the twig
        $datas = array(
            'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15), 
            'user' => Sentinel::check(), 
            'menuTabSelected' => '1');

        $this->view->render($response, 'homepage.twig', $datas);
		
        return $response;
    }
    
    /**
     * Method which runs a reseach
     */
    public function search(Request $request, Response $response, $args) {
        $this->logger->info('Start a research');
        
        $searchForm = $request->getParsedBody();
        
        $searchTextValue = filter_var($searchForm['searchQuery'], FILTER_SANITIZE_STRING);
        $searchQuery = str_replace(' ', '%', $searchTextValue); //Build basic SQL search query
    
        $filter = array();
    
        //Defines tables where the query as to be searched
        if(isset($searchForm['filter']))
            $filter[] = filter_var($searchForm['filter'], FILTER_SANITIZE_STRING);
        else {
            $filter[] = 'Photo';
            $filter[] = 'Tags';
            $filter[] = 'Place';
            $filter[] = 'User';
        }
                                               
        if(!empty($searchQuery)) {
            //Performs the search query
            $searchResults = array();
            
            foreach($filter as $fil) {
                switch($fil) {
                    case 'Photo':
                        $searchResults[$fil] = Photo::where('message', 'LIKE', '%'.$searchQuery.'%')->with('user', 'notes', 'place')->get();
                        break;
                    case 'Tags':
                        $searchResults[$fil] = Tags::where('name', 'LIKE', '%'.$searchQuery.'%')->get();
                        break;
                    case 'Place':
                        $searchResults[$fil] = Place::where('name', 'LIKE', '%'.$searchQuery.'%')->get();
                        break;
                    case 'User':
                        //A bit special case: we have to look for the username in 2 columns, so we build a specific query
                        $userSearchQuery = explode(' ', $searchTextValue); //cut search query in different words
                        
                        $userWhere = User::select(); 
                        
                        //For each query word, we look for DB entries which contain it in first name or last name column
                        foreach($userSearchQuery as $userQueryWord) {
                            $userWhere->where('first_name', 'LIKE', '%'.$userQueryWord.'%')->orWhere('last_name', 'LIKE', '%'.$userQueryWord.'%');
                        }
                        
                        $searchResults[$fil] = $userWhere->with('photos')->get(); //here we have all db entries which contain at least one of the query word
                        break;
                    default: 
                        //The given filter doesn't exist
                        break;
                }
            }
            
            //Preparation of datas to send to the twig
            $datas = array(
                'searchResults' => $searchResults,
                'searchQuery' => $searchTextValue,
                'menuActive' => 1,
                'user' => Sentinel::forceCheck());

            return $this->view->render($response, 'search.twig', $datas);  
        } else {
            //The search query can't be empty
            //Preparation of datas to send to the twig
            $datas = array(
                'success' => false, 
                'user' => Sentinel::check(), 
                'message' => 'Le champ de recherche ne doit pas être vide pour effectuer une recherche.',
                'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
}
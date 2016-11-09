<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\Photo;

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
}
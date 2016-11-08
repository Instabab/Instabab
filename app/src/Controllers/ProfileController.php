<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\User;

final class ProfileController
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
     * Method which display user profile from its id 
     * args - {id: user's id}
     */
    public function displayProfile(Request $request, Response $response, $args) {
        $this->logger->info('Start to display profile with id: '.$args['id']);
        $profile = User::find($args['id']); //Look for the user in DB
        
        if($profile != null) {
            //Profile found
            $this->logger->info('Profile '.$profile->id.' found: display profile');
            $this->view->render($response, 'profile.twig', array('profile' => $profile, 'posts' => $profile->photos()->with('user', 'place', 'notes')->get(), 'user' => Sentinel::forceCheck()));    
        } else {
            //Profile not found
            $this->logger->info('Error: profile '.$args['id'].' not found');
            $this->view->render($response, 'displayMessage.twig', array('success' => false, 
                                                                        'message' => 'L\'utilisateur recherché n\'existe pas/plus.'));
        }
    }
}
<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\User;
use App\Models\Photo;

final class UserController
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
     * Method which check form datas and register the user
     */
    public function registerUser(Request $request, Response $response, $args) {
        $this->logger->info("Start to register a new user");
        
        //Get form datas and filter them
        $data = $request->getParsedBody();
        $userForm = array();
        $userForm['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
        $userForm['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
        $userForm['passwordRepeat'] = filter_var($data['passwordVerification'], FILTER_SANITIZE_STRING);
        $userForm['first_name'] = filter_var($data['firstName'], FILTER_SANITIZE_STRING);
        $userForm['last_name'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
        $userForm['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
        
        if($userForm['password'] == $userForm['passwordRepeat']) {
            if(filter_var($userForm['email'], FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED)) {
                if(User::where('email', $userForm['email'])->get()->count() == 0) {
                    if(!empty($userForm['first_name']) && !empty($userForm['last_name'])) {
                        //Successful verifications
                        Sentinel::registerAndActivate($userForm);
                        
                        $datas = array( 
                            'success' => true,
                            'message' => 'Bravo '.$userForm['first_name'].' ! Vous avez bien été enregistré.',
                            'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
                        
                        return $this->view->render($response, 'displayMessage.twig', $datas);
                    } else {
                        $this->logger->info('Error: first_name and last_name can\'t be empty');
                        
                        $datas = array( 
                            'success' => false, 
                            'message' => 'Vous devez remplir les champs "nom" et "prénom" pour valider votre inscription.',
                            'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
                        
                        return $this->view->render($response, 'displayMessage.twig', $datas);
                    }
                } else {
                    $this->logger->info('Error: there is already a user using the given email address');
                    
                    $datas = array( 
                        'success' => false, 
                        'message' => 'Un utilisateur utilise déjà cette adresse e-mail.',
                        'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
                    
                    return $this->view->render($response, 'displayMessage.twig', $datas);
                }
            } else {
                $this->logger->info('Error: the given email address wasn\'t valid');
                
                $datas = array(
                    'success' => false, 
                    'message' => 'L\'adresse e-mail que vous avez fournie n\'est pas valide.', 
                    'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
                
                return $this->view->render($response, 'displayMessage.twig', $datas);
            }
        } else {
            $datas = array( 
                'success' => false, 
                'message' => 'Les deux mots de passe que vous avez entré ne correspondent pas.', 
                'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
            
            $this->logger->info('Error: password and passwordRepeat didn\'t match');
            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
        
    /**
     * Method which check form datas and login the user 
     */
     public function login(Request $request, Response $response, $args) {
         $this->logger->info("Start to login an user");

         //Get form datas and filter them
         $data = $request->getParsedBody();
         $userForm = array();
         $userForm['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
         $userForm['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
         
         if(filter_var($userForm['email'], FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED) && !empty($userForm['password'])) {
            //Verification of connexion
             if($userInfo = Sentinel::forceAuthenticate($userForm)){
                $datas = array(  
                    'success' => true,
                    'message' => 'Bravo '.$userInfo['first_name'].' '.$userInfo['last_name'].', vous êtes connecté.',
                    'connected' => true,
                    'user' => $userInfo,
                    'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15)); 
                 
                return $this->view->render($response, 'displayMessage.twig', $datas);
             } else {
                $datas = array( 
                    'success' => false, 
                    'message' => 'Mail ou mot de passe invalide !',
                    'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
                 
                return $this->view->render($response, 'displayMessage.twig', $datas);    
             }
         } else {
             $datas = array(   
                 'success' => false,                                                           
                 'message' => 'Mail invalide !',                                                          
                 'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
             
            return $this->view->render($response, 'displayMessage.twig', $datas);    
         }
         
     }
    
     /**
     * Method which logout the user 
     */
     public function logout(Request $request, Response $response, $args) {
         $this->logger->info("Start to logout an user");
         Sentinel::logout();
         
         $datas = array(
             'success'=>true, 
             'connected' => false, 
             'message' => 'Vous n\'êtes plus connecté.', 
             'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
         
         return $this->view->render($response, 'displayMessage.twig', $datas);
     }
    /**
     * Method which display an authentication error to the user (when he needs to be connected)
     */
    public function userNeedsToAuthenticate(Request $request, Response $response, $args) {
        $datas = array( 
            'success' => false,                                                               
            'message' => 'Vous devez être connecté pour accéder à cette page.',                                 
            'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
        
        return $this->view->render($response, 'displayMessage.twig', $datas);
    }
    
    /**
     * Method which display an authentication error to the user (when he needs to be disconnected)
     */
    public function userNeedsToLogout(Request $request, Response $response, $args) {
        $datas = array( 
            'success' => false,                                                               
            'message' => 'Vous ne pouvez pas accéder à cette page en étant connecté.',
            'user' => Sentinel::forceCheck(),
            'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15));
        
        return $this->view->render($response, 'displayMessage.twig', $datas);
    }
}
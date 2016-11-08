<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\User;

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
                        return $this->view->render($response, 'displayMessage.twig', array(   'success' => true,
                                                                                        'message' => 'Bravo '.$userForm['first_name'].' ! Vous avez bien été enregistré.'));

                    } else {
                        $this->logger->info('Error: first_name and last_name can\'t be empty');
                        return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                            'message' => 'Vous devez remplir les champs "nom" et "prénom" pour valider votre inscription.'));
                    }
                } else {
                    $this->logger->info('Error: there is already a user using the given email address');
                    return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                        'message' => 'Un utilisateur utilise déjà cette adresse e-mail.'));
                }
            } else {
                $this->logger->info('Error: the given email address wasn\'t valid');
                return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                    'message' => 'L\'adresse e-mail que vous avez fournie n\'est pas valide.'));
            }
        } else {
            $this->logger->info('Error: password and passwordRepeat didn\'t match');
            return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                'message' => 'Les deux mots de passe que vous avez entré ne correspondent pas.'));
        }
    }
    
    /**
     * Method which update user's profile
     */
    public function updateProfile(Request $request, Response $response, $args) {
        $this->logger->info('Start to update user profile');
        $user = Sentinel::check();
        
        $data = $request->getParsedBody();
        $userForm = array();
        $userForm['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
        $userForm['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
        $userForm['passwordRepeat'] = filter_var($data['passwordVerification'], FILTER_SANITIZE_STRING);
        $userForm['first_name'] = filter_var($data['firstName'], FILTER_SANITIZE_STRING);
        $userForm['last_name'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
        $userForm['description'] = nl2br(filter_var($data['description'], FILTER_SANITIZE_STRING));
  
        if($user->id == $args['id']) {
            //This is the right user
            if(empty($userForm['password']) || ($userForm['password'] == $userForm['passwordRepeat'])) {
                if(!empty($userForm['email']) && filter_var($userForm['email'], FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED)) {
                        //Successful verifications
                    
                        $userDataToUpdate = array();
                        
                        if(!empty($userForm['email']))
                            $userDataToUpdate['email'] = $userForm['email'];
                    
                        if(!empty($userForm['password']))
                            $userDataToUpdate['password'] = $userForm['password'];
                    
                        if(!empty($userForm['first_name']))
                            $userDataToUpdate['first_name'] = $userForm['first_name'];
                    
                        if(!empty($userForm['last_name']))
                            $userDataToUpdate['last_name'] = $userForm['last_name'];
                    
                        if(!empty($userForm['description']))
                            $user->description = $userForm['description'];
                    
                        Sentinel::update($user, $userDataToUpdate);
                        $user->save();
                        
                        return $this->view->render($response, 'displayMessage.twig', array( 'success' => true,
                                                                                            'message' => 'Votre profil a bien été mis à jour.'));
                } else {
                    $this->logger->info('Error: the given email address wasn\'t valid');
                    return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                        'message' => 'L\'adresse e-mail que vous avez fournie n\'est pas valide.'));
                }
            } else {
                $this->logger->info('Error: password and passwordRepeat didn\'t match');
                return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                    'message' => 'Les deux mots de passe que vous avez entré ne correspondent pas.'));
            }
        } else {
            //This isn't the right user
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
                 
                return $this->view->render($response, 'displayMessage.twig', array(  'success' => true,
                                                                            'message' => 'Bravo '.$userInfo['first_name'].' '.$userInfo['last_name'].', vous êtes connecté.',
                                                                            'connected' => true,
                                                                            'user' => $userInfo));
             } else {
                return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                                    'message' => 'Mail ou mot de passe invalide !'));    
             }
         } else {
              return $this->view->render($response, 'displayMessage.twig', array(   'success' => false,
                                                                                        'message' => 'Mail invalide !'));    
         }
         
     }
    
     /**
     * Method which logout the user 
     */
     public function logout(Request $request, Response $response, $args) {
         $this->logger->info("Start to logout an user");
         Sentinel::logout();
         
         return $this->view->render($response, 'displayMessage.twig', array('success'=>true, 'connected' => false, 'message' => 'Vous n\'êtes plus connecté.'));
     }
    /**
     * Method which display an authentication error to the user (when he needs to be connected)
     */
    public function userNeedsToAuthenticate(Request $request, Response $response, $args) {
        return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                            'message' => 'Vous devez être connecté pour accéder à cette page.'));
    }
    
    /**
     * Method which display an authentication error to the user (when he needs to be disconnected)
     */
    public function userNeedsToLogout(Request $request, Response $response, $args) {
        return $this->view->render($response, 'displayMessage.twig', array( 'success' => false, 
                                                                            'message' => 'Vous ne pouvez pas accéder à cette page en étant connecté.'));
    }
}
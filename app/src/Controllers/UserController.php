<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

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
        $userForm['passwordRepeat'] = filter_var($data['passwordRepeat'], FILTER_SANITIZE_STRING);
        $userForm['first_name'] = filter_var($data['first_name'], FILTER_SANITIZE_STRING);
        $userForm['last_name'] = filter_var($data['last_name'], FILTER_SANITIZE_STRING);
        $userForm['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
        
        if($userForm['password'] == $userForm['passwordRepeat']) {
            if(filter_var($userForm['email'], FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED)) {
                if(User::where('email', $userForm['email'])->get()->count() > 0) {
                    if(!empty($userForm['first_name']) && !empty($userForm['last_name'])) {
                        //Successful verifications
                        Sentinel::register($userForm);
                        
                        return $this->view->render($response, 'register.twig', array(   'success' => true, 
                                                                                        'message' => 'Bravo '.$userForm['first_name'].' ! Vous avez bien été enregistré.'));
                    } else {
                        $this->logger->info('Error: first_name and last_name can\'t be empty');
                        return $this->view->render($response, 'register.twig', array(   'success' => false, 
                                                                                        'message' => 'Vous devez remplir les champs "nom" et "prénom" pour valider votre inscription.'));
                    }
                } else {
                    $this->logger->info('Error: there is already a user using the given email address');
                    return $this->view->render($response, 'register.twig', array(   'success' => false, 
                                                                                    'message' => 'Un utilisateur utilise déjà cette adresse e-mail.'));
                }
            } else {
                $this->logger->info('Error: the given email address wasn\'t valid');
                return $this->view->render($response, 'register.twig', array(   'success' => false, 
                                                                                'message' => 'L\'adresse e-mail que vous avez fournie n\'est pas valide.'));
            }
        } else {
            $this->logger->info('Error: password and passwordRepeat didn\'t match');
            return $this->view->render($response, 'register.twig', array(   'success' => false, 
                                                                            'message' => 'Les deux mots de passe que vous avez entrez ne correspondent pas.'));
        }
    }
}
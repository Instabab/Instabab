<?php

namespace App\Controllers;

use App\Factories\BasicFactory;
use App\Factories\MessageFactory;
use App\Factories\TwigDataFactory;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use App\Models\User;
use App\Models\Photo;

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
            
            if(Sentinel::check()->id == $args['id'])
                $menuActive = 2;
            else
                $menuActive = 1;
            
            //Preparation of datas to send to the twig
            $datas = BasicFactory::make($menuActive);
            $datas['profile'] = $profile;
            $datas['posts'] = $profile->photos()->with('user', 'place', 'notes')->get()->sortByDesc('id');

            return $this->view->render($response, 'profile.twig', $datas);
        } else {
            //Profile not found
            $this->logger->info('Error: profile '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('L\'utilisateur recherché n\'existe pas ou plus.');

            return $this->view->render($response, 'displayMessage.twig', $datas);
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

                        $datas = MessageFactory::make('Votre profil a bien été mis à jour.', true);

                        return $this->view->render($response, 'displayMessage.twig', $datas);
                } else {
                    $this->logger->info('Error: the given email address wasn\'t valid');

                    $datas = MessageFactory::make('L\'adresse e-mail que vous avez fournie n\'est pas valide.');

                    return $this->view->render($response, 'displayMessage.twig', $datas);
                }
            } else {
                $this->logger->info('Error: password and passwordRepeat didn\'t match');

                $datas = MessageFactory::make('Les deux mots de passe que vous avez entré ne correspondent pas.');

                return $this->view->render($response, 'displayMessage.twig', $datas);
            }
        } else {
            //This isn't the right user
        }
    }
}
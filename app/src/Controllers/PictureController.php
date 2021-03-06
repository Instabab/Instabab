<?php

namespace App\Controllers;


use App\Factories\MessageFactory;
use App\Factories\BasicFactory;
use App\Models\Notes;
use App\Models\Photo;
use App\Models\Place;
use App\Models\Tags;
use App\Models\TagsPhotos;
use App\Models\Comments;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class PictureController
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
     * Method which add a picture to a profil
     */
    public function addPicture(Request $request, Response $response, $args)
    {
        $this->logger->info('Start to add picture');

        $success = true;
        $msg = 'Votre photo a bien été publiée.';
        
        if ($_FILES['pictureInput']['error'] > 0) {
            $msg = 'Erreur lors du transfert de la photo.';
            $success = false;
        } else {
            $user = Sentinel::check();
            $name = md5($user['email'] . time());
            $extension_upload = strtolower(substr(strrchr($_FILES['pictureInput']['name'], '.'), 1));
            $path = "images/pictures/$name.$extension_upload";
            $moveSuccess = move_uploaded_file($_FILES['pictureInput']['tmp_name'], $path);
            if (!$moveSuccess) {
                $msg = 'Une erreur est survenue lors du déplacement de la photo.';
                $success = false;
            } else {
                $data = $request->getParsedBody();

                $userId = $user['id'];

                $desc = $data['descPicture'];
                $tags = $this->searchTag($desc);
                $desc = filter_var($desc, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                //search for place or create a new place
                if (!($placeId = filter_var($data['place_chooser'], FILTER_VALIDATE_INT))) {
                    $msg = 'Choisissez un vrai kebab !';
                    $success = false;
                } else {
                    if ($placeId == '-1') {
                        $placeName = filter_var($data['placeName'], FILTER_SANITIZE_STRING);
                        $placeAddress = filter_var($data['placeAddress'], FILTER_SANITIZE_STRING);
                        if ($placeAddress == "" || $placeName == "") {
                            $datas = MessageFactory::make('L\'adresse et le nom du restaurant sont requis');
                            return $this->view->render($response, 'displayMessage.twig', $datas);
                        }
                        //look if the name place is already exist
                        if ($place = Place::where('name', $placeName)->where('address', $placeAddress)->first())
                            $placeId = $place->id;
                        else {
                            $place = new Place();
                            $place->name = $placeName;
                            $place->address = $placeAddress;
                            $place->save();
                            $placeId = $place->id;
                        }
                    }

                    $picture = new Photo();
                    $picture->message = $desc;
                    $picture->photo = '/' . $path;
                    $picture->id_place = $placeId;
                    $picture->id_user = $userId;
                    $picture->save();
                    $idPicture = $picture->id;

                    foreach ($tags as $t) {
                        $tag = Tags::where('name', $t)->first();
                        if (!$tag) {
                            $tag = new Tags();
                            $tag->name = $t;
                            $tag->save();
                        }
                        $idTag = $tag->id;

                        $desc = str_replace($t, '<a href="/tag/' . $idTag . '">' . $t . '</a>', $desc);

                        $tagsPhotos = new TagsPhotos();
                        $tagsPhotos->id_photo = $idPicture;
                        $tagsPhotos->id_tag = $idTag;
                        $tagsPhotos->save();
                    }

                    if (sizeof($tags) > 0) {
                        $picture->message = $desc;
                        $picture->update();
                    }
                }
            }
        }

        //Preparation of datas to send to the twig
        if($success) {
            return $response->withHeader('location', '/');
        } else {
            $datas = MessageFactory::make($msg, $success);

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }
    
    /**
     * Method which displays a publication
     */
    public function displayPost(Request $request, Response $response, $args) {
        $this->logger->info('Start to display a publication with id: '.$args['id']);
        $publication = Photo::with('notes', 'comments', 'user', 'place')->find($args['id']); //Look for the post in DB
        
        if($publication != null) {
            //Publication found
            $this->logger->info('Publication '.$publication->id.' found: display post');
            
            //Preparation of datas to send to the twig
            $datas = BasicFactory::make(1);
            $datas['publication'] = $publication;

            return $this->view->render($response, 'publication.twig', $datas);
        } else {
            //Publication not found
            $this->logger->info('Error: publication '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('La publication recherchée n\'existe pas ou plus.');

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }  
    }
    
    /**
     * Method which adds a comment on a publication
     */
    public function addComment(Request $request, Response $response, $args) {
        $this->logger->info('Start to add a new comment');
        $publication = Photo::find($args['id']); //Look for the post in DB
        
        if($publication != null) {
            //Publication found
            $this->logger->info('Publication '.$publication->id.' found: comment publication');
            
            $formData = $request->getParsedBody();
            $comment = filter_var($formData['comment'], FILTER_SANITIZE_STRING);
            
            if(!empty($comment)) {
                $this->logger->info('Successful verifications: insert comment in DB');
                
                //Insert the comment in the DB
                $commentObject = new Comments();
                $commentObject->message = $comment;
                $commentObject->id_user = Sentinel::check()->id;
                $commentObject->id_photo = $publication->id;
                $commentObject->save();
                               
                return $response->withHeader('location', '/post/'.$publication->id);
            } else {
                $this->logger->info('Error: comment can\'t be empty');
                
                //Preparation of datas to send to the twig
                $datas = MessageFactory::make('Vous ne pouvez pas laisser le champ commentaire vide.');

                return $this->view->render($response, 'displayMessage.twig', $datas);
            }
        } else {
            //Publication not found
            $this->logger->info('Error: publication '.$args['id'].' not found');
            
            //Preparation of datas to send to the twig
            $datas = MessageFactory::make('La publication que vous souhaitez commenter n\'existe pas ou plus.');

            return $this->view->render($response, 'displayMessage.twig', $datas);
        }
    }


    /**
     * function which search the tags into a description
     * @param $desc
     */
    private function searchTag($desc)
    {
        $isTag = false;
        $descSize = strlen($desc);
        $tags = array();
        $currentTag = '#';
        $currentChar = $desc[0];

        for ($pos = 0; $pos < $descSize; $pos++) {
            $currentChar = $desc[$pos];
            if ($isTag) {
                if (!(ctype_space($currentChar) || ctype_punct($currentChar))) {
                    $currentTag .= $currentChar;
                } else {
                    $tags[$currentTag] = $currentTag;
                    $isTag = false;
                }
            }
            if ($currentChar == '#') {
                $isTag = true;
                $currentTag = '#';
            }
        }
        if ($isTag)
            $tags[$currentTag] = $currentTag;

        return $tags;
    }

    /**
     * Method which adds a like on a picture
     */
    public function like (Request $request, Response $response, $args ){
        $this->logger->info('Start to like a publication');
        $user = Sentinel::forceCheck();
        $id = $args['id'];

        if(!$picture = Photo::find($id)){
            $datas = MessageFactory::make('La photo à aimer est introuvable, veuillez réessayer plus tard.');
            return $this->view->render($response, $request, $datas);
        }

        $newLocation = $this->likeOrDislike($id, $user->id, 1, $args, $picture);
        return $response->withHeader('location', $newLocation);

    }

    /**
     * Method which adds a dislike on a picture
     */
    public function dislike (Request $request, Response $response, $args ){
        $this->logger->info('Start to dislike a publication');
        $user = Sentinel::forceCheck();
        $id = $args['id'];

        if(!$picture = Photo::find($id)){
            $datas = MessageFactory::make('La photo à désapprouver est introuvable, veuillez réessayer plus tard.');
            return $this->view->render($response, $request, $datas);
        }

        $newLocation = $this->likeOrDislike($id, $user->id, -1, $args, $picture);
        return $response->withHeader('location', $newLocation);
    }

    /**
     * Private method which insert a like (or dislike) in DB
     */
    private function likeOrDislike($idPhoto, $idUser, $value, $args, $picture){

        //if the user has already like or dislike the picture
        if($note = Notes::where('id_photo', $idPhoto)->where('id_user', $idUser)->first()){
            if($note->value == ($value * -1)) {
                $note->value = $value;
                $note->update();
            } else
                $note->delete();
        }
        //if the user has not already like or dislike the picture
        else {
            $note = new Notes();
            $note->id_photo = $idPhoto;
            $note->id_user = $idUser;
            $note->value = $value;
            $note->save();
        }
        $newLocation = '';

        switch($args['type']) {
            case 'post':
                $newLocation = '/post/'.$idPhoto ;
                break;
            case 'place':
                $newLocation = '/place/'.$picture->id_place ;
                break;
            case 'profile':
                $newLocation = '/profile/'.$picture->id_user ;
                break;
            case 'tag':
                $newLocation = '/tag/'.$args['idTag'] ;
                break;
            case 'homePage':
                $newLocation = '/';
                break;
            default :
                $newLocation = '/';
                break;
        }

        return $newLocation;
    }


}
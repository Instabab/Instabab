<?php

namespace App\Controllers;

use App\Models\Photo;
use App\Models\Tags;
use App\Models\TagsPhotos;
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
    public function addPicture(Request $request, Response $response, $args) {
        $this->logger->info('Start to add picture');

        $user = Sentinel::check();
        if(!$user)
            return $this->view->render($response, 'displayMessage.twig', array('success' => false, 'message' => 'Il faut être connecté pour ajouter une photo'));
        if ($_FILES['pictureInput']['error'] > 0)
            return $this->view->render($response, 'displayMessage.twig', array('success' => false, 'message' => 'Erreur lors du transfert de la photo.'));

        $name = md5($user['email'].time());
        $extension_upload = strtolower(  substr(  strrchr($_FILES['pictureInput']['name'], '.')  ,1)  );
        $path = "images/pictures/$name.$extension_upload";
        $success = move_uploaded_file($_FILES['pictureInput']['tmp_name'],$path);
        if (!$success)  return $this->view->render($response, 'displayMessage.twig', array('success' => false, 'message' => 'Erreur lors du déplacement de la photo.'));

        $data = $request->getParsedBody();

        $userId = $user['id'];
        $desc = $data['descPicture'];
        $tags = $this->searchTag($desc);

        $picture = new Photo();
        $picture->message = $desc;
        $picture->photo = '/'.$path;
        $picture->id_place = 3;
        $picture->id_user = $userId;
        $picture->save();
        $idPicture =  $picture->id;

        foreach($tags as $t) {
            $tag = Tags::where('name', $t)->first();
            if(!$tag) {
                $tag = new Tags();
                $tag->name = $t;
                $tag->save();
            }
            $tagsPhotos = new TagsPhotos();
            $tagsPhotos->id_photo = $idPicture;
            $tagsPhotos->id_tag = $tag->id;
            $tagsPhotos->save();
        }

        return $this->view->render($response, 'displayMessage.twig', array('success' => true, 'message' => 'Photo publiée'
                                                                            , 'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15)));

    }

    /**
     * function which search the tags into a description
     * @param $desc
     */
    private function searchTag($desc){
        $pos = 0;
        $pos2 = 0;
        $tags = array();
        while($pos = stripos($desc,'#', $pos+1)){
            $pos2 = stripos($desc, ' ', $pos);
            if($pos2)
                $tag = substr($desc, $pos, $pos2-$pos);
            else
                $tag = substr($desc, $pos);
            array_push($tags, $tag);
        }

        return $tags;
    }
}
<?php

namespace App\Controllers;


use App\Factories\MessageFactory;
use App\Factories\BasicFactory;
use App\Models\Photo;
use App\Models\Place;
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
    public function addPicture(Request $request, Response $response, $args)
    {
        $this->logger->info('Start to add picture');

        $success = true;
        $msg = 'Photo publiée';


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
                $msg = 'Erreur lors du déplacement de la photo.';
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
        $datas = MessageFactory::make($msg, $success);

        return $this->view->render($response, 'displayMessage.twig', $datas);

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

}
<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Json\Json;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Decoder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListeSeriesController extends AbstractActionController
{

    private $_idPage;
    private $_idRecherche;

    public function __construct()
    {

    }

    public function listeseriesAction()
    {
        // -----------Requete sur Trakt------------- //

        //Récupère idpage depuis url (ne se reset pas à 1 quand on fait une nouvelle recherche)
        $this->_idPage=$this->params()->fromRoute('page');

        // Url de la requete
        $url = 'https://api.trakt.tv/search/show?query='.$this->_idRecherche.'&page='.$this->_idPage;

        //Prépare requete
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($url);
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));

        //Envoie requete
        $client = new Client();
        $api = $client->send($request);

        //Décope le json vers php
        $resultat = Json::decode($api->getBody());

        // Récupère le header pour l'url de la requete pour savoir le nombre de page reçu
        $header = get_headers($url, 1);
        $nbPages = (int)$header["X-Pagination-Page-Count"];

        // -----------Requete sur OMDB------------- //
        
        $arrayImages = array();
        $idOMDB = "";

        // Pour chaque element de la recherche on va chercher son image dans OMDB
        for($i=0; $i<sizeof($resultat);$i++) {
            // Récupère l'id de la série pour OMDB
            $idOMDB = $resultat[$i]->show->ids->imdb;

            //Prépare requete
            $requestOMDB = new Request();
            $requestOMDB->setMethod(Request::METHOD_GET);
            $requestOMDB->setUri('http://www.omdbapi.com/?i='.$idOMDB.'&apikey=215947ec');
            $requestOMDB->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));

            //Envoie requete
            $clientOMDB = new Client();
            $apiOMDB = $clientOMDB->send($requestOMDB);

            //Décope le json vers php
            $resultatOMDB = Json::decode($apiOMDB->getBody());
            $arrayImages[$i] = $resultatOMDB;
        }
        
        return new ViewModel([
            'recherche'=>$this->_idRecherche,
            'resultat'=>$resultat,
            'imageOMDB' => $arrayImages,
            'numPage' =>$this->_idPage,
            'nbPages' => $nbPages
        ]);
    }
}

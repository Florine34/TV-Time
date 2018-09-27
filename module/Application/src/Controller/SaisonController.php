<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use User\Services\UtilisateurBadgeTable;
use Zend\Json\Json;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Decoder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Services\UserManager;
use User\Services\UtilisateurSerieTable;
use User\Models\Utilisateurserie;
use User\Models\Utilisateurbadge;
use User\Services\UtilisateurEpisodeSerieTable;
use User\Models\Utilisateurepisodeserie; 


class SaisonController extends AbstractActionController
{

    private $_idSerie;
    private $_idSaison;
    private $_idEpisode;

    private $authService;
    private $userManager;
    private $_utilisateurSerie;
    private $_utilisateurBadge;
    private $_utilisateurEpisodeSerie;
    

    public function __construct($authService,UserManager $userManager,UtilisateurSerieTable $utilisateurSerie,UtilisateurEpisodeSerieTable $utilisateurEpisodeSerie,UtilisateurBadgeTable $utilisateurBadge)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->_utilisateurSerie = $utilisateurSerie;
        $this->_utilisateurBadge = $utilisateurBadge;
        $this->_utilisateurEpisodeSerie = $utilisateurEpisodeSerie;
    }

    public function saisonAction()
    {
        //Récupère de la série et de la saison depuis l'url
        $idSerie = $this->_idSerie=$this->params()->fromRoute('idSerie');
        $idSaison = $this->_idSaison=$this->params()->fromRoute('idSaison');
        // Recupère l'id User 
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;

        //Prépare requete pour obtenir les informations concernant la saison en fonction de l'id serie et l'id saison. Utilisation de l'API Trakt Tv
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri('http://api.trakt.tv/shows/'.$this->_idSerie.'/seasons/'.$this->_idSaison);
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));

        //Prépare requete pour obtenir toutes les informations sur la série en fonction de son id.
        $request2 = new Request();
        $request2->setMethod(Request::METHOD_GET);
        $request2->setUri('http://api.trakt.tv/shows/'.$this->_idSerie.'?extended=full');
        $request2->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));
        $client = new Client();
        // Envois des requetes 
        $api = $client->send($request);
        $api2 = $client->send($request2);
        //Décode le json vers php
        $serie = Json::decode($api2->getBody());
        $arrayImages = array();

        // Récupère l'imdb de la série afin d'obtenir le poster 
        $idOMDB = $serie->ids->imdb;

        //Prépare requete afin d'obtenir le poster de la série. 
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

        //Décode le json vers php
        $episode = Json::decode($api->getBody());
        $resultatOMDB = Json::decode($apiOMDB->getBody()); 
        // A partir du résultat de la requête, on peut compter le nombre d'épisode dans la série
        $nbEpisode= count($episode) ;
        $tab = array(); 
        // On parcours tous les épisodes et on met a true la valeur de tab si l'épisode se trouve dans la table utilisateurepisodeserie c'est à dire si il a été vus
        for($i=0 ; $i<$nbEpisode+1; $i++){
            $req= $this->_utilisateurEpisodeSerie->select($idUser, $idSerie, $idSaison, $i);
            if($req!=null){
                $tab[$i]=true;
            }
            else{
                $tab[$i]=false;
            }
        }
        
        // On retourne toutes les valeurs afin de les utiliser dans la vue saison.phtml
        return new ViewModel([
            'idSerie'=>$this->_idSerie,
            'idSaison'=>$this->_idSaison,
            'episode'=>$episode,
            'tab'=>$tab,
            'image'=>$resultatOMDB,
        ]);
    }

    // Fonction permettant de marquer un épisode comme vus. 
    public function checkAction(){
        // On récupère l'id user et l'id serie
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        $idSerie = $this->_idSerie=$this->params()->fromRoute('idSerie');
        
        // On selectionne le nombre d'épisode vus 
        $nbEpisodeVus=$this->_utilisateurSerie->findByIdSerieUser($idUser, $idSerie);
        $nbEpisodeVusIncrément=$this->_utilisateurSerie->findByIdSerieUser($idUser, $idSerie);
        // On incrémente de 1 la variable nbepisode vus si l'épisode a été vus 
        $nbEpisodeVusIncrément->_episodesVus =  $nbEpisodeVusIncrément->_episodesVus + 1 ; 
        $nbEpisodeFinal= (array) ($nbEpisodeVusIncrément) ; 
        // Update de la base de données 
        $nbEpisodeVusUpdate = $this->_utilisateurSerie->UpdateNbEpisode($nbEpisodeVus, $nbEpisodeFinal);

        //On récupère l'id saison et l'id épisode 
        $idSaison = $this->_idSaison=$this->params()->fromRoute('idSaison');
        $idEpisode = $this->_idEpisode=$this->params()->fromRoute('idCheck'); 
        // On créé l'objet avec les nouvelles valeurs 
        $object = new Utilisateurepisodeserie(); 
        $object->_idUtilisateur =$idUser; 
        $object->_idSerie = $idSerie; 
        $object->_idSaison = $idSaison;
        $object->_idEpisode = $idEpisode ; 
        $object->_note = 0 ; 

        // On ajoute l'épisode vus dans la table utilisateurepisodeserie
        $ajouterEpisode = $this->_utilisateurEpisodeSerie->insert($object);

        //Gestion des badges
        $this->_utilisateurBadge->testBadges($idUser);

         // On retourne toutes les valeurs afin de les utiliser dans la vue saison.phtml
        return $this->redirect()->toRoute('saison', array(
            'action' => 'saison',
            'idSerie' =>$idSerie,
            'idSaison'=>$idSaison,
        ));
    }

    // Fonction permetant de marquer un épisode comme non vus 
    public function unCheckAction(){
        // On récupère l'id user et l'id serie
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        $idSerie = $this->_idSerie=$this->params()->fromRoute('idSerie');
        
        // On selectionne le nombre d'épisode vus 
        $nbEpisodeVus=$this->_utilisateurSerie->findByIdSerieUser($idUser, $idSerie);
        $nbEpisodeVusIncrément=$this->_utilisateurSerie->findByIdSerieUser($idUser, $idSerie);
        
        // On décrémente de 1 la variable nbepisode vus si l'épisode a été marqué non vus 
        $nbEpisodeVusIncrément->_episodesVus =  $nbEpisodeVusIncrément->_episodesVus - 1 ; 
        $nbEpisodeFinal= (array) ($nbEpisodeVusIncrément) ; 
        $nbEpisodeVusUpdate = $this->_utilisateurSerie->UpdateNbEpisode($nbEpisodeVus, $nbEpisodeFinal); 

        // On récupère l'id saison et l'id épisode
        $idSaison = $this->_idSaison=$this->params()->fromRoute('idSaison');
        $idEpisode = $this->_idEpisode=$this->params()->fromRoute('idUnCheck'); 
        //Suppression dans la base utilisateur episode serie
        $supprimerEpisode = $this->_utilisateurEpisodeSerie->delete($idUser, $idSerie, $idSaison, $idEpisode);

        // On retourne toutes les valeurs afin de les utiliser dans la vue saison.phtml
        return $this->redirect()->toRoute('saison', array(
            'action' => 'saison',
            'idSerie' =>$idSerie,
            'idSaison'=>$idSaison,
        ));
    }
}

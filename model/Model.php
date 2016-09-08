<?php

/**
 * Description of Club
 *
 * @author Tissot David
 */
//autoloader
/* include_once ("model/Livre.php");
  include_once ("model/Adherent.php");
  include_once ('model/Database.php'); */


spl_autoload_extensions(".php");
spl_autoload_register();

class Model {

    private $_dbConnexion;

    public function __construct() {
        $this->_dbConnexion = new Database('localhost', 'root', '', 'FFBSQ');
    }

    public function getClubList() {
        $listClubs = array();
        $clubs = $this->_dbConnexion->requete("SELECT * FROM Club");
        $licences = $this->_dbConnexion->requete("SELECT * FROM Licence");
        foreach ($clubs as $club) {
            $tempClub = new Club($club->id, $club->nom, $club->adresse, $club->email);
            foreach ($licences as $licence) {
                if ($licence->idClub == $tempClub->getId()) {
                    $tempClub->ajouterLicence(new Licence($licence->numero, $licence->nomResp, $licence->prenomResp, $licence->idClub));
                }
            }
            array_push($listClubs, $tempClub);
        }
        return $listClubs;
    }

    public function getClub($id) {
        $listClubs = $this->getClubList();
        foreach ($listClubs as $club) {
            if ($club->getId() == $id) {
                return $club;
            }
        }
    }

    public function getAdherentList() {
        $listAdherents = array();
        $adherents = $this->_dbConnexion->requete("SELECT * FROM adherent");
        foreach ($adherents as $adherent) {
            array_push($listAdherents, new Adherent($adherent->id, $adherent->nom, $adherent->prenom, $adherent->tel, $adherent->inscription));
        }
        return $listAdherents;
    }

    public function getAdherent($id) {
        $listAdherents = $this->getAdherentList();
        foreach ($listAdherents as $adherent) {
            if ($adherent->getId() == $id) {
                return $adherent;
            }
        }
    }

    public function emprunter($id, $livre) {
        $adherent = $this->getAdherent($id);
        $livre->emprunter($adherent);
        $this->_dbConnexion->reqUpdate("UPDATE livre
                                         SET idEmprunteur = " . $id .
                " WHERE isbn = " . $livre->getIsbn());
    }

}

?>
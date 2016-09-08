<?php

include_once("model/Model.php");

class Controller {

    public $_model;

    public function __construct() {
        $this->_model = new Model();
    }

    public function invoke() {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case "rechercher":
                    $club = $this->_model->getClub($_GET['id']);
                    include 'view/RechercheClub.php';
                    break;
                case "licences":
                    $club = $this->_model->getClub($_GET['club']);
                    include 'view/listeLicence.php';
                    break;
                default:
                    echo "Erreur";
            }
        } else {
            $clubs = $this->_model->getClubList();
            include 'view/listeClub.php';
        }
    }

}

?>
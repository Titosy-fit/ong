<?php
class Demande extends CI_Controller
{
    private $projets ; 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DemandeModel', 'demande');
        $this->load->model('RemiseModel', 'remise');
        $this->load->model('DispatchModel', 'dispatch');
        $this->load->model('UniteModel', 'unite');
        $this->load->model('ProjetModel', 'projet');

        $this->projets = $this->projet->getpojet() ; 
    }


    public function index()
    {
        $this->jail();
        // $this->jail() ; 
        $data['title'] = 'Demande';
        $data['css'] = 'demande.css';
        $js['js'] = 'demande.js';

        $pv = $this->dispatch->getAllVente();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);


        $this->load->view('demande', [
            'pv' => $pv,
            'projets' => $this->projets 
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * facturation de l'emprunt
     *
     * @return void
     */
    public function facturation()
    {
        $idUser = '';
        if (isset($_POST['idUser']) && $_POST['idUser'] != '') {
            $idUser = strip_tags(trim($this->input->post('idUser')));
        }

        $id_pointdevente = '';
        if (isset($_POST['id_pointdevente']) && $_POST['id_pointdevente'] != '') {
            $id_pointdevente = (int) strip_tags(trim($this->input->post('id_pointdevente')));
        }
        $idprojet = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = (int) strip_tags(trim($this->input->post('idprojet')));
        }

        $lastFacture = $this->dispatch->getLastFacture('emprunt');

        if (!empty($lastFacture)) {
            $last = $lastFacture[0]->Facture;
            $explodeLastFacture = explode("-", $last);
            $numAI = intval($explodeLastFacture[1]);
            $numAI++;
            $numFacture = "DM-" . str_pad($numAI, 5, 0, STR_PAD_LEFT);
        } else {
            $numFacture = "DM-" . str_pad("1", 5, 0, STR_PAD_LEFT);
        }


        $data = [
            'Facture' => $numFacture,
            'idprojet' => $idprojet,
            'idUser' => $_SESSION['id_user'] ?? '',
            'idagent' => $idUser,
            'idPointVente' => $id_pointdevente,
            'type_dispatch' => 'emprunt',
            'idadmin' => $_SESSION['idadmin'],
        ];

        $idfacture = $this->dispatch->insertVente($data);
        echo json_encode([
            'facture' => $numFacture,
            'idfacture' => $idfacture,
        ]);
    }

    /**
     * exactement comme dans le dispatch 
     *
     * @return void
     */
    public function validate() {}

    /**
     * Prendre les info du demande par la numero de demande 
     *
     * @return void
     */
    public function getByfact()
    {
        $facture  = '';


        if (isset($_POST['facture']) && $_POST['facture'] != '') {
            $facture  = strip_tags(trim($this->input->post('facture')));
        }

        $datas = $this->demande->getByfact($facture);
        foreach ($datas as $key => $data) {
            $returned = $this->remise->getpanierremise( $data->idPanier ) ;
            if ( count( $returned )) {
                $datas[ $key ]->quantite -= $returned[0]->quantite_remise ; 
            }
        }
        if (count($datas)) {
            echo json_encode([
                'success' => true,
                'datas' => $datas
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'datas' => $datas
            ]);
        }
    }
}

<?php

class Remise extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RemiseModel', 'remise');
        $this->load->model('PointVente_model', 'pv');
        $this->load->model('UniteModel', 'unite');
        $this->load->model('UserModel', 'user');


        // 
        $this->load->model('ProjetModel', 'projet');
    }


    /**
     * page principale  de remise materiel
     *
     * @return void
     */
    public function index()
    {
        $this->jail();

        $nPages = ceil($this->remise->get_count() / PAGINATION);
        $current = 1;

        $datas = $this->remise->get_authors(1);


        $agents = $this->user->getAllUser();

        $data['title'] = 'Remise du matériel';
        $data['css'] = 'remise.css';
        $js['js'] = 'remise.js';

        $pv = $this->pv->getAllPv();
        $projets = $this->projet->getpojet();
        $mat = $this->remise->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('remise', [
            'pv' => $pv,
            'agents' => $agents,
            'nPages' => $nPages,
            'current' => $current,
            'datas' => $datas
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * pagination
     *
     * @param integer $page
     * @return void
     */
    public function page($page = 1)
    {
        $this->jail();

        $nPages = ceil($this->remise->get_count() / PAGINATION);
        $current = $page;

        $datas = $this->remise->get_authors($page);


        $agents = $this->user->getAllUser();

        $data['title'] = 'Remise du matériel';
        $data['css'] = 'remise.css';
        $js['js'] = 'remise.js';

        $pv = $this->pv->getAllPv();
        $projets = $this->projet->getpojet();
        $mat = $this->remise->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('remise', [
            'pv' => $pv,
            'agents' => $agents,
            'nPages' => $nPages,
            'current' => $current,
            'datas' => $datas
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Enregistrement de l'approvisionnement
     *
     * @return void
     */
    public function register()
    {
        $pv = '';
        if (isset($_POST['pv']) && $_POST['pv'] != '') {
            $pv = trim(strip_tags($this->input->post('pv')));
        }
        $agent = 1;
        if (isset($_POST['agent']) && $_POST['agent'] != '') {
            $agent = trim(strip_tags($_POST['agent']));
        }
        $iddispatch = '';
        if (isset($_POST['iddispatch']) && $_POST['iddispatch'] != '') {
            $iddispatch = trim(strip_tags($_POST['iddispatch']));
        }

        $idunites = [];
        if (isset($_POST['idunite'])) {
            $idunites = $_POST['idunite'];
        }
        $panier = [];
        if (isset($_POST['panier'])) {
            $panier = $_POST['panier'];
        }
        $quantite = [];
        if (isset($_POST['quantite'])) {
            $quantite = $_POST['quantite'];
        }
        $materiel = [];
        if (isset($_POST['materiel'])) {
            $materiel = $_POST['materiel'];
        }




        $data_panier = [];
        for ($i = 0; $i < count($quantite); $i++) {
            $min_qte = 0;
            $data = [];
            if ($quantite[$i] > 0) {
                // convertion on l'unite le plus petit 
                $min_qte = $this->convertion($idunites[$i], $quantite[$i]);
                if ($min_qte == 0) {
                    $min_qte = $quantite[$i];
                }
                $data = [
                    'quantite_remise' => $quantite[$i],
                    'idunite' => $idunites[$i],
                    'idPanier' => $panier[$i],
                    'min_qte' => $min_qte,
                    'idmateriel' => $materiel[$i],
                    'idadmin' => $_SESSION['idadmin'],
                ];

                $le_panier = $this->remise->getpanierbyid($panier[$i]);
                if (count($le_panier)) {
                    $sum_returned = $this->remise->get_sum_remise($panier[$i]);
                    $total_retourne = $min_qte + ($sum_returned->min_qte ?? 0);

                    if ($total_retourne >= $le_panier[0]->min_qte) {
                        $this->remise->return($panier[$i]);
                    }
                    $data_panier[] = $data;
                }
                $min_qte = 0;
            }
        }
        if (count($data_panier)) {
            $data_remise = [
                'idfacture' => $iddispatch,
                'idagent' => $agent,
                'iduser' => $_SESSION['id_user'] ?? '',
                'idPointVente' => $pv,
                'idadmin' => $_SESSION['idadmin']
            ];

            $idremise = $this->remise->insertremise($data_remise);

            for ($i = 0; $i < count($data_panier); $i++) {
                $data_panier[$i]['idremise'] = $idremise;
            }
            $this->remise->insertremisepanier($data_panier);
            $this->session->set_userdata('added', true);
            redirect('rendre-mat');
        } else {
            redirect('rendre-mat');
        }
    }

    /**
     * delete remise
     *
     * @return void
     */
    public function delete()
    {
        $id = $this->input->post('idremise');

        $data = $this->remise->delete($id);

        $this->session->set_flashdata('delete', 'Ajout réussie');

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }


    /**
     * recherche dans remise
     *
     * @param integer $page
     * @return void
     */
    public function search($page = 1)
    {
        // date
        $date_debut = trim(strip_tags($_POST['date_debut']));
        $heure_debut = trim(strip_tags($_POST['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;

        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;

        if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '')
            $heure_fin .= '23:59:59';



        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date;
        $mot = strip_tags(trim($_POST['recherche']));
        $_POST['mot'] = $mot;

        $recherche = $this->remise->searchDate($page,  $date_debut, $date_fin, $mot);

        $nPages = ceil($this->remise->searchDate($page,  $date_debut, $date_fin, $mot, true) / PAGINATION);
        $current = $page;

        $pv = $this->pv->getAllPv();
        $data['title'] = 'Appro';
        $data['css'] = 'remise.css';
        $js['js'] = 'remise.js';


        $agents = $this->user->getAllUser();
        $mat = $this->remise->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ["remise" => true]);
        $this->load->view('templates/tete');
        $this->load->view('remise', [
            'datas' => $recherche,
            'pv' => $pv,
            'mat' => $mat,
            'nPages' => $nPages,
            'current' => $current,
            'agents' => $agents
        ]);

        $this->load->view('templates/footer', $js);
    }


    /**
     * convertion du quantite donner en quantite de l'unite minimale
     *
     * @param [type] $idunite
     * @param [type] $qte
     * @return void
     */
    private function convertion($idunite, $qte)
    {
        // convertire la quantite d'une unite en l'unite assicier le plus bas 
        $test = true;
        while ($test && $idunite != '') {
            $response = $this->unite->convertion($idunite);
            if (count($response) > 0) {
                $idunite = $response[0]->idunite;
                $qte = $qte * $response[0]->formule;
            } else {
                $test = false;
            }
        };

        return (int)$qte;
    }


    /**
     * details par partie 
     *
     * @return void
     */
    public function getDetails()
    {
        $idremise = '';
        if (isset($_POST['idremise']) && $_POST['idremise'] != '') {
            $idremise = strip_tags(trim($_POST['idremise']));
        }

        $remise = $this->remise->getbyid($idremise);
        $remise = $remise[0];
        $remise_paniers = $this->remise->getpanier($idremise);



        $affichage = '<div class ="row w-100 m-auto " >';

        // Agent 
        $affichage .= "<div class='col-4  pt-1'>";


        $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> N° Agent </span><span  '> : " . $remise->telagent . "</span></p>";

        $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Nom </span> <span  '> : " . strtoupper($remise->nomagent) . "</span></p>";
        $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Prénom </span> <span  '>  : "  . ucfirst($remise->prenomagent) . "</span></p>";

        $affichage .= "</div>";

        $affichage .= '<div style="border-top : 1px solid #cfcfcfcf " class ="row w-100  m-auto pt-1 ">';

        $affichage .= '<div class="_tableau">';
        $affichage .= '<table class="table">';
        $affichage .= '<thead class="table-info">';
        $affichage .= '<tr>  
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Déscription</th>
                    <th>Quantité</th>
                  </tr>
                </thead>
              <tbody>';

        foreach ($remise_paniers as $key => $panier) {
            $affichage .= "<tr>";
            $affichage .= "<td>" . $panier->refmateriel . "</td>";
            $affichage .= "<td>" . $panier->designationmateriel . "</td>";
            $affichage .= "<td>" . $panier->fiche . "</td>";


            if ($panier->quantite_remise > 1) {
                if (isset($panier->denomination)) {
                    $affichage .= "<td>" . $panier->quantite_remise . ' ' . $panier->denomination .  "(s) </td>";
                } else {
                    $affichage .= "<td>" . $panier->quantite_remise . "</td>";
                }
            } else {
                $affichage .= "<td>" . $panier->quantite_remise . "</td>";
            }

            $affichage .= "</tr>";
        }

        $affichage .= '</tbody>
      </table>
      </div class>';

        $affichage .= '<div class ="_boutton " >';
        $affichage .= "<div class='bg-success p-2 text-center text-light ' style ='border-radius:1px'>";

        if ($remise->idUser == null) {
            $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Le distributeur </span><span  '> : Admin</span></p>";
        } else {
            $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Le distributeur  </span><span  '> : " . $remise->nomUser . " " .  $remise->prenomUser  . "</span></p>";
        }

        $affichage .= "</div>";
        $affichage .= "<div class='bg-info p-2 text-light text-center ' style ='border-radius:1px'>";
        $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Dépôt </span> <span  '> : " . $remise->denomination_pv . ' ' . $remise->adressPv . "</span></p>";
        $affichage .= "</div>";
        $affichage .= "</div>";

        echo $affichage;
    }

    // ************ utile *************** //
}

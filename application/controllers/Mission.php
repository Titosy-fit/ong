<?php
class Mission extends CI_Controller
{
    private $projets;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('MissionModel', 'mission');
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('LiquidationModel', 'liquidation');
        $this->projets = $this->projet->getpojet();

        $this->jail();


        // if (  $_SESSION['user_type'] != 'admin'){
        //     redirect('stock') ; 
        // }
    }


    /**
     * Page pricipale du mission
     *
     * @return void
     */
    // public function index()
    // {
    //     $page = isset($_GET['page']) ? $_GET['page'] : 0;
    //     $datasall =  $this->mission->getmission();

    //     //var_dump($this->mission->getmission()) ;
    //     $datapag['mission'] = $this->mission->getmission($page);
    //     $datapag['lien'] = $this->pagination('mission' , count( $datasall ));


    //     $data['title'] = 'Projet';
    //     $data['css'] = 'mission.css';
    //     $js['js'] = 'mission.js';

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('templates/sidebar', ['mission' => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('mission', [
    //         'data' => $datapag,
    //         'projets' => $this->projets , 
    //         'current' => 1
    //     ]);
    //     $this->load->view('templates/footer', $js);
    // }
    public function index()
    {
        $data['title'] = 'Mission';
        $data['css']   = 'mission.css';
        $js['js']      = 'mission.js';

        $this->jail();

        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        if ($page < 1) $page = 1;

        // Compteur performant (pas besoin de charger toutes les lignes)
        $total = $this->mission->count_missions();

        // Données paginées
        $datapag['mission'] = $this->mission->getmission($page);

        $datapag['lien'] = $this->_pagination_links(
            base_url('mission'),
            $total,
            PAGINATION ?? 10
        );

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mission' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mission', [
            'data'     => $datapag,
            'projets'  => $this->projets,
            'current'  => $page
        ]);
        $this->load->view('templates/footer', $js);
    }


    /**
     * verification du doublant
     *
     * @return void
     */
    public function verify()
    {
        $om  = '';
        if (isset($_POST['om']) && $_POST['om'] != '') {
            $om = trim(strip_tags($_POST['om']));
        }

        $asm  = '';
        if (isset($_POST['asm']) && $_POST['asm'] != '') {
            $asm = trim(strip_tags($_POST['asm']));
        }

        $idmission  = '';
        if (isset($_POST['idmission']) && $_POST['idmission'] != '') {
            $idmission = trim(strip_tags($_POST['idmission']));
        }

        $teste_om = $this->mission->verify_om($om,  $idmission);
        $teste_asm = $this->mission->verify_asm($asm, $idmission);

        if (count($teste_om) > 0  && count($teste_asm) > 0) {
            echo json_encode([
                'success' => false,
                'type' => 'both'
            ]);
        } else if (count($teste_om)) {
            echo json_encode([
                'success' => false,
                'type' => 'om'
            ]);
        } else if (count($teste_asm)) {
            echo json_encode([
                'success' => false,
                'type' => 'asm'
            ]);
        } else {
            echo json_encode([
                'success' => true
            ]);
        }
    }

    /**
     * enregistrement du mission
     *
     * @return void
     */
    public function register()
    {
        $type  = '';
        if (isset($_POST['typeMission']) && $_POST['typeMission'] != '') {
            $type = trim(strip_tags($_POST['typeMission']));
        }
        $idbudget  = '';
        if (isset($_POST['idbudget']) && $_POST['idbudget'] != '') {
            $idbudget = trim(strip_tags($_POST['idbudget']), ',');
        }

        $idagent  = 0;
        if (isset($_POST['idagent']) && $_POST['idagent'] != '') {
            $idagent = intval($_POST['idagent']);
        }
        $activite  = '';
        if (isset($_POST['activite']) && $_POST['activite'] != '') {
            $activite = trim(strip_tags($_POST['activite']));
        }
        $objet  = '';
        if (isset($_POST['objet']) && $_POST['objet'] != '') {
            $objet = trim(strip_tags($_POST['objet']));
        }
        $asm  = '';
        if (isset($_POST['asm']) && $_POST['asm'] != '') {
            $asm = trim(strip_tags($_POST['asm']));
        }
        $om  = '';
        if (isset($_POST['om']) && $_POST['om'] != '') {
            $om = trim(strip_tags($_POST['om']));
        }
        $lieu  = '';
        if (isset($_POST['lieu']) && $_POST['lieu'] != '') {
            $lieu = trim(strip_tags($_POST['lieu']));
        }
        $date_debut  = '';
        if (isset($_POST['date_debut']) && $_POST['date_debut'] != '') {
            $date_debut = trim(strip_tags($_POST['date_debut']));
        }
        $date_fin  = '';
        if (isset($_POST['date_fin']) && $_POST['date_fin'] != '') {
            $date_fin = trim(strip_tags($_POST['date_fin']));
        }
        $avance  = '';
        if (isset($_POST['avance']) && $_POST['avance'] != '') {
            $avance = trim(strip_tags($_POST['avance']));
        }
        $date_reception  = '';
        if (isset($_POST['date_reception']) && $_POST['date_reception'] != '') {
            $date_reception = trim(strip_tags($_POST['date_reception']));
        }
        $projet  = 0;
        if (isset($_POST['projet']) && $_POST['projet'] != '') {
            $projet = intval($_POST['projet']);
        }

        $idUser = '';
        if (isset($_SESSION['id_user'])) {
            $idUser = $_SESSION['id_user'];
        }

        $data = [
            'date_reception_avence' => $date_reception,
            'numero_asm' => $asm,
            'numero_om' => $om,
            'lieu_mission' => $lieu,
            'objet_mission' => $objet,
            'montant_avance' => $avance,
            'date_debut_mission' => $date_debut,
            'date_fin_mission' => $date_fin,
            'idagent' => $idagent,
            'idadmin' => $_SESSION['idadmin'],
            'idUser' => $idUser,
            'idprojet' =>  $projet,
            'typeMission' => $type
        ];

        $avances = [];
        if (is_array($_POST["avances"])) {
            $avances = $_POST["avances"];
        }
        $idmission = $this->mission->register($data);
        if ($avances) {
            foreach ($avances as $idbudget => $montant) {
                $idbudget = (int) $idbudget;
                $montant  = (float) $montant;   // TOUJOURS cast / valider !
                $data = [
                    'idbudget ' => $idbudget,
                    'montant_avance'  => $montant,
                    'idmission ' => $idmission
                ];

                $this->mission->insertAvanceMission($data);
            }
        }

        $this->session->set_userdata('added_projet',  true);

        redirect('mission');
    }

    // donner mission par id 
    public function donner()
    {
        $idmission  = '';
        if (isset($_POST['idmission']) && $_POST['idmission'] != '') {
            $idmission = trim(strip_tags($_POST['idmission']));
        }

        $data = $this->mission->donner($idmission);
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data
            ]);
        }
    }
    public function getData_json2()
    {
        $om  = '';

        if (isset($_POST['om']) && $_POST['om'] != '') {
            $om = trim(strip_tags($_POST['om']));
        }

        $data = $this->mission->obtenirMission($om);

        if (count($data)) {
            if ($this->liquidation->verifyLiquidation($data[0]->idmission) != 0) {


                $liquid = $this->liquidation->getInfoLiquidation($data[0]->idmission);

                $montant = $this->liquidation->getTotalMontantReturn($liquid->idliquidation);
                $totalReturn = $montant + $this->liquidation->getMontantReturnByLiquidation2($liquid->idliquidation);

                echo json_encode([
                    'success' => true,
                    'data' => $data,
                    'totalDepense' => $liquid->montant_depense,
                    'totalRetourne' => $totalReturn,
                    'idMission' => $data[0]->idmission,
                    'idLiquidation' => $liquid->idliquidation

                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'type' => 'liquide'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'type' => 'data'
            ]);
        }
    }

    //     public function getData_json2()
    // {
    //     $om = trim(strip_tags($_POST['om'] ?? ''));

    //     $data = $this->mission->obtenirMission($om);

    //     if (empty($data)) {
    //         echo json_encode(['success' => false, 'type' => 'data']);
    //         return;
    //     }

    //     $idmission = $data[0]->idmission;

    //     if ($this->liquidation->verifyLiquidation($idmission) == 0) {
    //         echo json_encode(['success' => false, 'type' => 'liquide']);
    //         return;
    //     }

    //     $liquid = $this->liquidation->getInfoLiquidation($idmission);
    //     $idliquidation = $liquid->idliquidation;

    //     // ──── IMPORTANT ────
    //     $somme_rembourse_depuis_relique = (float) $this->liquidation->getTotalMontantReturn($idliquidation);

    //     $reliquat_restant = max(0, $liquid->montant_reliquat - $somme_rembourse_depuis_relique);

    //     echo json_encode([
    //         'success'                     => true,
    //         'data'                        => $data,
    //         'totalDepense'                => $liquid->montant_depense,
    //         'totalRetourneViaRelique'     => $somme_rembourse_depuis_relique,     // ← renommé pour clarté
    //         'montantReturnALaLiquidation' => (float) $liquid->montant_return,     // ← valeur initiale
    //         'reliquatInitial'             => (float) $liquid->montant_reliquat,
    //         'reliquatRestant'             => $reliquat_restant,
    //         'idMission'                   => $idmission,
    //         'idLiquidation'               => $idliquidation
    //     ]);
    // }
    public function getData_json()
    {
        $om  = '';

        if (isset($_POST['om']) && $_POST['om'] != '') {
            $om = trim(strip_tags($_POST['om']));
        }

        $data = $this->mission->obtenirMission($om);

        if (count($data)) {
            if ($this->liquidation->verifyLiquidation($data[0]->idmission) != 0) {

                echo json_encode([
                    'success' => false,
                    'type' => 'liquide'
                ]);
            } else {

                echo json_encode([
                    'success' => true,
                    'data' => $data

                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'type' => 'data'
            ]);
        }
    }

    /**
     * Modification
     *
     * @return void
     */
    public function edit()
    {

        $idmission  = '';
        if (isset($_POST['idmission']) && $_POST['idmission'] != '') {
            $idmission = trim(strip_tags($_POST['idmission']));
        }
        $objet  = '';
        if (isset($_POST['objet']) && $_POST['objet'] != '') {
            $objet = trim(strip_tags($_POST['objet']));
        }
        $asm  = '';
        if (isset($_POST['asm']) && $_POST['asm'] != '') {
            $asm = trim(strip_tags($_POST['asm']));
        }
        $om  = '';
        if (isset($_POST['om']) && $_POST['om'] != '') {
            $om = trim(strip_tags($_POST['om']));
        }
        $lieu  = '';
        if (isset($_POST['lieu']) && $_POST['lieu'] != '') {
            $lieu = trim(strip_tags($_POST['lieu']));
        }
        $date_debut  = '';
        if (isset($_POST['date_debut']) && $_POST['date_debut'] != '') {
            $date_debut = trim(strip_tags($_POST['date_debut']));
        }
        $date_fin  = '';
        if (isset($_POST['date_fin']) && $_POST['date_fin'] != '') {
            $date_fin = trim(strip_tags($_POST['date_fin']));
        }

        $data = [
            'numero_asm' => $asm,
            'numero_om' => $om,
            'lieu_mission' => $lieu,
            'objet_mission' => $objet,
            'date_debut_mission' => $date_debut,
            'date_fin_mission' => $date_fin,
        ];


        $this->mission->edit($data, $idmission);
        $this->session->set_userdata('adit_projet',  true);

        redirect('mission');
    }

    /**
     * effacer l'mission
     *
     * @return void
     */
    public function delete()
    {
        $idmission = $this->input->post('idmission');

        if ($idmission) {
            $this->mission->delete($idmission);
            $this->session->set_userdata('delete_projet', true);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    /**
     * recherche dans mission
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

        $mot = trim(strip_tags($_POST['mot']));
        $_POST['mot'] = $mot;

        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        $datasall =  $this->mission->getmission_search($mot, $date_debut, $date_fin);

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['mission'] = $this->mission->getmission_search($mot, $date_debut, $date_fin, $page);


        $data['title'] = 'Projet';
        $data['css'] = 'mission.css';
        $js['js'] = 'mission.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mission' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mission', [
            'data' => $datapag,
            'nPages' => $nPages,
            'projets' => $this->projets,
            'current' => $page,
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function details()
    {
        $idmission  = '';
        if (isset($_POST['idmission']) && $_POST['idmission'] != '') {
            $idmission = trim(strip_tags($_POST['idmission']));
        }

        $datas = $this->mission->details($idmission);


        $affichage = '<div class="_tableau">';
        $affichage .= '<table class="table">';
        $affichage .= '<thead class="table-info">';
        $affichage .= '<tr>  
                    <th>Ligne budgétaire</th>
                    <th>Intitulé</th>
                    <th>Montant alloué</th>
                    <th>Montant engagé</th>
                  </tr>
                </thead>
              <tbody>';

        foreach ($datas as $key => $data) {
            $affichage .= "<tr>";
            $affichage .= "<td>" . $data->ligne_budget  . "</td>";
            $affichage .= "<td>" . $data->designation_budget  . "</td>";
            $affichage .= "<td>" . number_three($data->budget)  . "</td>";
            $affichage .= "<td>" . number_three($data->avTemp)  . "</td>";
            $affichage .= "</tr>";
        }

        $affichage .= '</tbody>
      </table>
      </div>';

        echo $affichage;
    }

    public function projets($id = '')
    {
        if ($id != '') {
            $temps = [];
            $projets_  = [];
            if (count($this->projets)) {
                foreach ($this->projets  as $key => $projet) {
                    if ($projet->idprojet == $id) {
                        $projets_[] = $projet;
                    }
                }
                foreach ($this->projets  as $key => $projet) {
                    if ($projet->idprojet != $id) {
                        $projets_[] = $projet;
                    }
                }
            }
            return $projets_;
        }
        return $this->projets;
    }



    /**
     * recherche par projet
     *
     * @param integer $page
     * @return void
     */
    public function searchProjet()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $idprojet  = '';
        if (isset($_GET['projet']) && $_GET['projet'] != '') {
            $idprojet = trim(strip_tags($_GET['projet']));
        }

        $_POST['idprojet'] = $idprojet;

        $datapag['mission'] = $this->mission->searchProjet($idprojet, $page);
        $datapag['lien'] = $this->pagination_search('Mission/searchProjet', count($this->mission->searchProjet($idprojet)));


        $data['title'] = 'Mission';
        $data['css'] = 'mission.css';
        $js['js'] = 'mission.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mission' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mission', [
            'data' => $datapag,
            'projets' => $this->projets($idprojet),
            'current' => $page,
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function filtreliquidation()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $idprojet  = '';
        if (isset($_GET['projet']) && $_GET['projet'] != '') {
            $idprojet = trim(strip_tags($_GET['projet']));
        }

        $_POST['filtre'] = true;

        $_POST['idprojet'] = $idprojet;

        $datapag['mission'] = $this->mission->filtreliquidation($idprojet, $page);
        $datapag['lien'] = $this->pagination_search('Mission/filtre-liquidation', count($this->mission->filtreliquidation($idprojet)));

        $data['title'] = 'Mission';
        $data['css'] = 'mission.css';
        $js['js'] = 'mission.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mission' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mission', [
            'data' => $datapag,
            'projets' => $this->projets($idprojet),
            'current' => $page,
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function filtreReliquat()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $idprojet  = '';
        if (isset($_GET['projet']) && $_GET['projet'] != '') {
            $idprojet = trim(strip_tags($_GET['projet']));
        }

        $_POST['filtre'] = true;


        $_POST['idprojet'] = $idprojet;

        $datapag['mission'] = $this->mission->filtreReliquat($idprojet, $page);
        $datapag['lien'] = $this->pagination_search('Mission/filtre-reliquat', count($this->mission->filtreReliquat($idprojet)));

        $data['title'] = 'Mission';
        $data['css'] = 'mission.css';
        $js['js'] = 'mission.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mission' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mission', [
            'data' => $datapag,
            'projets' => $this->projets($idprojet),
            'current' => $page,
        ]);
        $this->load->view('templates/footer', $js);
    }
    private function _pagination_links($base_url, $total_rows, $per_page = PAGINATION)
    {
        $this->load->library('pagination');

        $config = [];

        $config['base_url']             = $base_url;
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['use_page_numbers']     = TRUE;
        $config['page_query_string']    = TRUE;          // → ?page=2
        $config['query_string_segment'] = 'page';

        // Style Bootstrap (comme dans Budget / Reliquat)
        $config['full_tag_open']    = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav>';

        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '</span></li>';

        $config['attributes']       = ['class' => 'page-link'];

        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';

        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';

        $config['prev_link']        = 'Previous';
        $config['next_link']        = 'Next';

        // Si tu préfères des flèches : décommente ça
        // $config['prev_link']     = '&laquo;';
        // $config['next_link']     = '&raquo;';

        $config['num_links']        = 2;  // → ... 3 4 5 6 ...

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }
}

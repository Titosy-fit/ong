<?php
class Liquidation extends CI_Controller
{
    private $activites;
    private $budgets;
    private $projets;
    private $agents;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LiquidationModel', 'liquidation');
        $this->load->model("EtatModel", 'etat');
        $this->load->model("ActiviteModel", 'activite');
        $this->load->model("BudgetModel", 'budget');
        $this->load->model("UserModel", 'user');
        $this->load->model("MissionModel", 'mission');
        $this->load->model('ProjetModel', 'projet');

        $this->projets = $this->projet->getpojet();


        // activite du moment present
        $this->activites = $this->activite->getActiviteActuel();
        $this->agents = $this->user->getAllUser();

        if (count($this->activites)) {
            $this->budgets = $this->budget->getByactivite($this->activites[0]->idactivite);
        } else {
            $this->budgets = [];
        }
    }

    public function jail()
    {
        if (!isset($_SESSION['user_type'])) {
            redirect('connexion');
        }
    }


    /**
     * page principale 
     *
     * @param integer $page
     * @return void
     */
    // public function index($page = 1)
    // {
    //     $assets['css'] = 'liquidation.css';
    //     $assets['title'] = 'Liquidation';
    //     $assets['js'] = 'liquidation.js';
    //     $this->jail();


    //     $nPages = ceil(count($this->liquidation->get_authors()) / PAGINATION);
    //     $current = 1;

    //     $datas = $this->liquidation->get_authors($current);


    //     // $alldep = $this->liquidation->getAlldep();
    //     // $somme = $this->liquidation->getsomme($alldep);

    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar', ["liste" => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('liquidation', [
    //         'nPages' => $nPages,
    //         'current' => $current,
    //         'datas' => $datas,
    //         'projets' => $this->projets,
    //     ]);
    //     $this->load->view('templates/footer', $assets);
    // }


    // /**
    //  * pagination
    //  *
    //  * @param integer $page
    //  * @return void
    //  */
    // public function page($page = 1)
    // {
    //     $assets['css'] = 'liquidation.css';
    //     $assets['title'] = 'Liquidation';
    //     $assets['js'] = 'liquidation.js';
    //     $this->jail();


    //     $nPages = ceil(count($this->liquidation->get_authors()) / PAGINATION);
    //     $current = $page;

    //     $datas = $this->liquidation->get_authors($current);


    //     // $alldep = $this->liquidation->getAlldep();
    //     // $somme = $this->liquidation->getsomme($alldep);

    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar', ["liste" => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('liquidation', [
    //         'nPages' => $nPages,
    //         'current' => $current,
    //         'datas' => $datas,
    //         'projets' => $this->projets,
    //     ]);
    //     $this->load->view('templates/footer', $assets);
    // }
public function index()
{
    $assets['css']   = 'liquidation.css';
    $assets['title'] = 'Liquidation';
    $assets['js']    = 'liquidation.js';

    $this->jail();

    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    if ($page < 1) $page = 1;

    // Compteur performant
    $total = $this->liquidation->count_authors();
    
    // Données paginées
    $datas = $this->liquidation->get_authors($page);

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ['liquidation' => true]);
    $this->load->view('templates/tete');
    $this->load->view('liquidation', [
        'datas'    => $datas,
        'projets'  => $this->projets,
        'current'  => $page,
        'nPages'   => ceil($total / PAGINATION)
    ]);
    $this->load->view('templates/footer', $assets);
}

    private function montant_ar_to_int($montant = '')
    {
        $montant = str_replace('Ar', '', $montant);
        $montant = str_replace('AR', '', $montant);
        $montant = str_replace('aR', '', $montant);
        $montant = str_replace('aR', '', $montant);
        $montant = str_replace(' ', '', $montant);
        return $montant;
    }


    /**
     * enregistrement
     *
     * @return void
     */
    public function register()
    {

        $idmission = '';
        if (isset($_POST['idmission']) && $_POST['idmission'] != '') {
            $idmission = strip_tags(trim($_POST['idmission']));
        }
        $idagent = '';
        if (isset($_POST['idagent']) && $_POST['idagent'] != '') {
            $idagent = strip_tags(trim($_POST['idagent']));
        }
        $reliquat = 0;
        if (isset($_POST['nonRetournerHidden']) && $_POST['nonRetournerHidden'] != '') {
            $reliquat = intval($_POST['nonRetournerHidden']);
        }
        $retourner = '';
        if (isset($_POST['retourner']) && $_POST['retourner'] != '') {
            $retourner = strip_tags(trim($_POST['retourner']));
        }
        $_depenser = 0;
        if (isset($_POST['_depenser']) && $_POST['_depenser'] != '') {
            $_depenser = intval($_POST['_depenser']);
        }

        $date = '';
        if (isset($_POST['date']) && $_POST['date'] != '') {
            $date = strip_tags(trim($_POST['date']));
        }
        $heure = '';
        if (isset($_POST['heure']) && $_POST['heure'] != '') {
            $heure = strip_tags(trim($_POST['heure']));
        }

        $the_date = '';

        if ($date == '') {
            $the_date = date("Y-m-d H:i:s");
        } else {
            if ($heure == '') {
                $the_date = $date . ' 00:00:00';
            } else {
                $the_date = $date . ' ' . $heure . ':' . date("s");
            }
        }

        $budget = [];
        if (isset($_POST['budget'])) {
            $budget = $_POST['budget'];
        }
        $designation = [];
        if (isset($_POST['designation'])) {
            $designation = $_POST['designation'];
        }
        $montant = [];
        if (isset($_POST['montant'])) {
            $montant = $_POST['montant'];
        }
        $justification = [];
        if (isset($_FILES['justification'])) {
            $justification = $_FILES['justification'];
        }


        $tovalide = false;
        for ($i = 0; $i  < count($budget); $i++) {
            for ($k = 0; $k < count($designation[$i]); $k++) {
                if (
                    $designation[$i][$k] != ''
                    && $montant[$i][$k] != ''
                    
                ) {
                    $tovalide = true;
                    break;
                }
            }
        }

        if ($tovalide) {
            $data = [
                'idmission' => $idmission,
                'idagent' => $idagent,
                'idadmin' => $_SESSION['idadmin'],
                'idUser' => $_SESSION['id_user'] ?? '',
                'idagent' => $idagent,
                'date_reliquat' =>  $the_date,
                'montant_return' =>  $retourner,
                'montant_reliquat' => $reliquat,
                'montant_depense' => $_depenser,
            ];

            // set the isliquidate mission to true 
            $this->mission->setidliquide(true, $idmission);

            $idliquidation = $this->liquidation->register($data);
            $data_liquidation_depense = [];
            for ($i = 0; $i  < count($budget); $i++) {
                for ($k = 0; $k < count($designation[$i]); $k++) {
                    if ($designation[$i][$k] != '' && $montant[$i][$k] != '') {
                        $filename = $justification['name'][$i][$k];
                        if($filename!="") {
                             $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            $unique_name = time() . '_' . uniqid('logo_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;
                            $photo = move_uploaded_file($justification['tmp_name'][$i][$k], 'public/upload/justification/' . $unique_name);

                            $temps = [
                                'idliquidation' => $idliquidation,
                                'idbudget' => $budget[$i],
                                'justificatif' => 'public/upload/justification/' . $unique_name,
                                'raison' => $designation[$i][$k],
                                'montant_depense' => $montant[$i][$k],
                                'idadmin' => $_SESSION['idadmin'],
                                'idmission' => $idmission
                            ];

                            
                        }
                        else {
                            $temps = [
                                'idliquidation' => $idliquidation,
                                'idbudget' => $budget[$i],
                               
                                'raison' => $designation[$i][$k],
                                'montant_depense' => $montant[$i][$k],
                                'idadmin' => $_SESSION['idadmin'],
                                'idmission' => $idmission
                            ];
                        }
                        $data_liquidation_depense[] = $temps;
                        $temps = [];
                    }
                }
            }


            $this->session->set_userdata('added', true);
            $this->liquidation->insert_liquidation_depense_batch($data_liquidation_depense);
        } else {
            $this->session->set_userdata('erreur_register', true);
        }

        redirect('liquidation');
    }


    /**
     * suppression
     *
     * @return void
     */
    public function delete()
    {
        $idliquidation = '';
        if (isset($_POST['idliquidation']) && $_POST['idliquidation'] != '') {
            $idliquidation = strip_tags(trim($_POST['idliquidation']));
        }

        $this->liquidation->delete($idliquidation);

        echo json_encode([
            'success' => true
        ]);
    }


    /**
     * Modification
     *
     * @return void
     */
    public function edit()
    {
        $id = trim(strip_tags($_POST['id']));
        $montant = trim(strip_tags($_POST['montant_']));
        $raison = trim(strip_tags($_POST['raison_']));


        if ($montant != '' && $raison != "" &&  $id != '') {
            $data = [
                'montant' => $montant,
                'raison' => $raison
            ];

            $this->liquidation->edit($id, $data);
            $this->session->set_flashdata('edition', 'ok');
            redirect('Liquidation');
        }
        redirect('liquidation');
    }

    /**
     * recherche dans liquidation 
     *
     * @param integer $page
     * @return void
     */
    public function search($page = 1)
    {

        $assets['css'] = 'liquidation.css';
        $assets['title'] = 'Liquidation';
        $assets['js'] = 'liquidation.js';
        $this->jail();

        // date
        $date_debut = trim(strip_tags($_POST['date_debut']));
        $heure_debut = trim(strip_tags($_POST['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;
        // if ($heure_debut == '' && $date_debut != '')
        //     $heure_debut = '00:00:00';
        // else 
        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;
        // if ($heure_fin == '' && $date_fin != '')
        //     $heure_fin = '00:00:00';
        // else 
        if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '')
            $heure_fin .= '23:59:59';


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date
        $mot = '';
        if (isset($_POST['recherche']) && $_POST['recherche'] != '') {
            $mot = trim(strip_tags($_POST['recherche']));
        }
        $_POST['mot'] = $mot;

        $nPages = ceil(count($this->liquidation->getdataDep_search($mot, $date_debut, $date_fin)) / PAGINATION);
        $current = $page;
        $datas = $this->liquidation->getdataDep_search($mot,  $date_debut, $date_fin, $page);


        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('liquidation', [
            'nPages' => $nPages,
            'current' => $current,
            'datas' => $datas,
            'projets' => $this->projets,
        ]);
        $this->load->view('templates/footer', $assets);
    }


    public function details()
    {
        $idliquidation = 0;
        if (isset($_POST['idliquidation']) && $_POST['idliquidation'] != '') {
            $idliquidation = intval($_POST['idliquidation']);
        }


        $datas = $this->liquidation->details($idliquidation);

        $affichage = '<div class="_tableau">';
        $affichage .= '<table class="table">';
        $affichage .= '<thead class="table-info">';
        $affichage .= '<tr>  
                    <th>Ligne budgétaire</th>
                    <th>Intitulé</th>
                    <th>Montant avancé</th>
                    <th>Motif de la dépense</th>
                    <th>Montant</th>
                    <th>Pièce justificative</th>
                  </tr>
                </thead>
              <tbody>';

        foreach ($datas as $key => $data) {
            $affichage .= "<tr>";
            $affichage .= "<td>" . $data->ligne_budget . "</td>";
            $affichage .= "<td>" . $data->designation_budget . "</td>";
            $affichage .= "<td>" . number_three($data->avance) . "</td>";
            $affichage .= "<td>" . $data->raison . "</td>";
            $affichage .= "<td>" . number_three($data->montant_depense) . "</td>";
            
            if($data->justificatif==null) {
                $affichage .= '<td>
                                <div id="img_justificatif">
                                   Pièce manquante

                                </div>
                            </td>';
            }
            else {
                $affichage .= '<td>
                                <div id="img_justificatif">
                                    <img src="' . Myurl($data->justificatif)  . '" alt="Justificatif">
                                    <a href="'. Myurl($data->justificatif)  .'" download style="text-decoration: none;">
  
                                        <i class="fa-solid fa-download"></i> Télécharger
  
                                    </a>

                                </div>
                            </td>';
            }
            

            $affichage .= "</tr>";
        }

        $affichage .= '</tbody>
      </table>
      </div class>';

        echo $affichage;
    }

    public function projets( $id = '' ){
        if ( $id != ''){
            $temps = [] ; 
            $projets_  = [] ; 
            if ( count( $this->projets )){
                foreach ($this->projets  as $key => $projet ) {
                    if ( $projet->idprojet == $id ){
                        $projets_ [] = $projet ; 
                    }
                }
                foreach ($this->projets  as $key => $projet ) {
                    if ( $projet->idprojet != $id ){
                        $projets_ [] = $projet ; 
                    }
                }
            }
            return $projets_ ; 
        }
        return $this->projets ; 
    }


    /**
     * recherche par projet
     *
     * @param integer $page
     * @return void
     */
    public function searchProjet($page = 1)
    {

        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }

        $_POST['idprojet'] = $idprojet;

        $nPages = ceil(count($this->liquidation->searchProjet($idprojet)) / PAGINATION);
        $datas= $this->liquidation->searchProjet($idprojet, $page);


        $assets['css'] = 'liquidation.css';
        $assets['title'] = 'Liquidation';
        $assets['js'] = 'liquidation.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['liquidation' => true]);
        $this->load->view('templates/tete');
        $this->load->view('liquidation', [
            'datas' => $datas,
            'nPages' => $nPages,
            'current' => $page,
            'projets' => $this->projets($idprojet)
        ]);
        $this->load->view('templates/footer', $assets );
    }
}

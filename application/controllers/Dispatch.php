<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dispatch extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("DispatchModel", "dispatch");
        $this->load->model("MaterielModel", "materiel");
        $this->load->model('ClientsModel', 'client');
        $this->load->model('FactureModel', 'facture');
        $this->load->model('StockModel', 'stock');
        $this->load->model('ListeModel', 'liste');
        $this->load->model('Admin_model');
    }
    public function rechercheClient()
    {
        $this->jail();
        $pv = $this->dispatch->getAllVente();
        $prod = $this->dispatch->getAllProd();

        $data['title'] = 'Dispatch';
        $data['css'] = 'dispatch.css';
        $js['js'] = 'dispatch.js';

        $keyword = strip_tags(trim($_GET['recherche']));

        $_POST['post'] = $keyword;
        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'rechercheClient';
        $config["total_rows"] = count($this->client->searchClients($keyword, '', ''));
        $config["per_page"] = PAGINATION;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['client'] = $this->client->searchClients($keyword, $config["per_page"], $start);
        // * pagination * // 


        $this->session->set_flashdata('vente_client', 'ok');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);
        $this->load->view('dispatch', [
            'pv' => $pv,
            'prod' => $prod,
            'data' => $datapag,
            'post' => $_POST['post']
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function rechercher_mat_vente()
    {
        $this->jail();
        $ref = strip_tags(trim($this->input->post('ref')));
        $pv = strip_tags(trim($this->input->post('pv')));

        $num = trim($ref);

        $res = $this->dispatch->getVenteNum($num, $pv);

        // echo '<pre>' ; 
        // var_dump( $pv ) ;
        // echo '</pre>' ; die  ; 

        if (count($res) > 0) {
            if ($res[0]->etat_vente != 'Non vendu') {
                echo json_encode([
                    'success' => false,
                    'type' => 'vendu'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'data' => $res[0]
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'type' => 'unexist'
            ]);
        }


        // $iteration = 0;
        // $data = [];
        // $all = $this->stock->getAll();

        // for ($i = 0; $i < count($all); $i++) {
        //     $qte = $this->stock->getQTE($all[$i]->refmateriel, $all[$i]->idPointVente);
        //     $exist = false;
        //     for ($k = 0; $k < count($data); $k++) {
        //         if (ucfirst($data[$k]->refmateriel) == ucfirst($all[$i]->refmateriel)) {
        //             if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
        //                 $exist = true;
        //             }
        //         }
        //     }
        //     if (!$exist) {
        //         $data[$iteration] = $all[$i];
        //         $data[$iteration]->qte = $qte[0]->qte;
        //         $iteration++;
        //     }
        // }


        // $dispatch = $this->stock->dispatch();
        // for ($v = 0; $v < count($dispatch); $v++) {
        //     for ($i = 0; $i < count($data); $i++) {
        //         if (ucfirst($data[$i]->refmateriel) == ucfirst($dispatch[$v]->refmateriel) && $data[$i]->idPointVente == $dispatch[$v]->idPointVente) {
        //             $data[$i]->qte -= $dispatch[$v]->qteProduit;
        //         }
        //     }
        // }

        // for ($a = 0; $a < count($data); $a++) {
        //     if (ucfirst($data[$a]->refmateriel) == ucfirst($ref) && $data[$a]->adressPv == $pv) {
        //         $quantiter = $data[$a]->qte;
        //     }
        // }
    }

    public function getPriceByOffre()
    {
        $this->jail();
        $ref = $this->input->post('ref');
        $data = $this->v_model->price($ref);
        echo json_encode(['prix' => $data[0]->prixOffre, 'jour' => $data[0]->dureeOffre, 'id' => $data[0]->idOffre]);
    }

    public function getpv()
    {
        $this->jail();
        $pv = $this->dispatch->selectPv();
        echo json_encode($pv);
    }


    public function pv()
    {
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'commercial') {
                $pv = $this->poste->selectPostByIdCommercial($_SESSION['idCommercial']);
            } elseif ($_SESSION['user_type'] == 'adminPv') {
                $pv = $this->adminPv->selectAdminPvByIdAdminPv($_SESSION['idAdminPv']);
            }
        }

        echo json_encode($pv);
    }

    public function get()
    {
        $reference = $this->input->post('reference');
        $designationmateriel = $this->input->post('designationmateriel');
        $prix = $this->input->post('prix');
        $quantiter = $this->input->post('quantiter');
        $montant = $this->input->post('montant');
        $numClient = $this->input->post('numClient');
        $modePay = $this->input->post('modePay');
        $adressePointVente = $this->input->post('adressePointVente');
        $idMateriel = $this->input->post('idMateriel');
    }

    /**
     * ajout des materiel dans le panier 
     *
     * @return void
     */
    public function validate()
    {

        $datas = [];
        if (isset($_POST['data'])) {
            $datas = $_POST['data'];
        }

        foreach ($datas  as $key => $data) {
            $datas[$key]['idadmin'] = $_SESSION['idadmin'];
        }
        $this->dispatch->insertPanier($datas);
        $this->session->set_flashdata('success', true);
        $this->session->set_userdata('ajouter', true);
    }

    /**
     * Enregistrement et creation du nouvelle facture 
     *
     * @return void
     */
    public function facturation()
    {
        $idbeneficiaire = '';
        if (isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire'] != '') {
            $idbeneficiaire = strip_tags(trim($this->input->post('idbeneficiaire')));
            $idbeneficiaire = str_replace(' ', '', $idbeneficiaire);
        }

        $id_pointdevente = '';
        if (isset($_POST['id_pointdevente']) && $_POST['id_pointdevente'] != '') {
            $id_pointdevente = (int) strip_tags(trim($this->input->post('id_pointdevente')));
        }
        $idprojet = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = (int) strip_tags(trim($this->input->post('idprojet')));
        }

        $lastFacture = $this->dispatch->getLastFacture();

        if (!empty($lastFacture)) {
            $last = $lastFacture[0]->Facture;
            $explodeLastFacture = explode("-", $last);
            $numAI = intval($explodeLastFacture[1]);
            $numAI++;
            $numFacture = date("Y") . "-" . str_pad($numAI, 5, 0, STR_PAD_LEFT);
        } else {
            $numFacture = date("Y") . "-" . str_pad("1", 5, 0, STR_PAD_LEFT);
        }


        $data = [
            'Facture' => $numFacture,
            'idbeneficiaire' => $idbeneficiaire,
            'idUser' => $_SESSION['id_user'] ?? '',
            'idPointVente' => $id_pointdevente,
            'idprojet' => $idprojet,
            'type_dispatch' => 'vente',
            'idadmin' => $_SESSION['idadmin'],
        ];

        $idfacture = $this->dispatch->insertVente($data);
        echo json_encode([
            'facture' => $numFacture,
            'idfacture' => $idfacture,
        ]);
    }

    public function verifyClient()
    {
        $num = $this->input->post('num-client');
        $data = $this->v_model->getClientByNum($num);

        if (count($data) > 0) {
            echo json_encode([
                'exist' => true,
                'data' => $data,
            ]);
        } else {
            echo json_encode([
                'exist' => false,
                'data' => [],
            ]);
        }
    }


    public function recupLignes()
    {
        $key = $this->input->post('key');
        $data = $this->v_model->getLigneWithKey($key);

        $nPagesLigne = ceil($this->v_model->countLigneWithKey($key) / LIMITE);
        $currentLigne = 1;


        $affichage = "<table class='table table-striped mt-3'>";
        $affichage .= "<thead class='bg-primary'>";
        $affichage .= "<tr><th></th><th class='text-white'>Référence ligne</th><th class='text-white'>Numéro de ligne</th><th class='text-white'>Capacité</th></tr>";
        $affichage .= "</thead>";
        $affichage .= "</tbody>";

        for ($i = 0; $i < count($data); $i++) {
            $affichage .= "<tr>";
            $affichage .= "<td><input type='checkbox' class='checkbox' data-numligne='" . $data[$i]->numeroPuce . "' data-cap='" . $data[$i]->capacite . "' status='off' data-ref='" . $data[$i]->refLigne . "'></td>";
            $affichage .= "<td>" . $data[$i]->refLigne . "</td>";
            $affichage .= "<td>" . $data[$i]->numeroPuce . "</td>";
            $affichage .= "<td>" . $data[$i]->capacite . "</td>";

            $affichage .= "</tr>";
        }

        $affichage .= "</tbody>";
        $affichage .= "</table>";


        if ($nPagesLigne > 1) {
            $affichage .= '
            <div class="__pagination">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li>';

            if ($currentLigne == 1) {
                $affichage .= '
                                <span class="__disabled" aria-label="Previous">
                                    <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                </span>';
            } else {
                $affichage .= '
                                <button onclick="paginateLigne(event,this)" type="button" data-href="';
                $affichage .= base_url("dispatch/ligne/filtre/" . ($currentLigne - 1));
                $affichage .= '" aria-label="Previous" data-key="' . $key . '">
                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                </button>
                                ';
            }

            $affichage .= '</li>';

            for ($i = 1; $i <= $nPagesLigne; $i++) {
                $affichage .= '<li class="';

                if ($i == $currentLigne) {
                    $affichage .= 'active';
                }

                $affichage .= '">

                                <button onclick="paginateLigne(event,this)" type="button" data-href="' . base_url('dispatch/ligne/filtre/' . $i) . '" data-key="' . $key . '">' . $i . '</button>

                            </li>';
            }

            $affichage .= '<li>';

            if ($currentLigne == $nPagesLigne) {
                $affichage .= '
                            <span class="__disabled" aria-label="Next">
                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                            </span>';
            } else {

                $affichage .= '
                                <button onclick="paginateLigne(event,this)" type="button" data-href="' . base_url('dispatch/ligne/filtre/' . ($currentLigne + 1)) . '" aria-label="Next" data-key="' . $key . '">
                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                </button>
                            ';
            }
            $affichage .=
                '</li>
                    </ul>
                </nav>
            </div>';
        }


        echo $affichage;
    }

    public function searchNumSerie()
    {
        $num = strip_tags(trim($_POST['num']));
        $pv = strip_tags(trim($_POST['pv']));


        $res = $this->dispatch->getNumSerie($pv, $num);

        $iteration = 0;
        $data = [];
        $all = $this->stock->getAll();

        for ($i = 0; $i < count($all); $i++) {
            $qte = $this->stock->getQTE($all[$i]->refmateriel, $all[$i]->idPointVente);
            $exist = false;
            for ($k = 0; $k < count($data); $k++) {
                if (ucfirst($data[$k]->refmateriel) == ucfirst($all[$i]->refmateriel)) {
                    if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
                        $exist = true;
                    }
                }
            }
            if (!$exist) {
                $data[$iteration] = $all[$i];
                $data[$iteration]->qte = $qte[0]->qte;
                $iteration++;
            }
        }
        $quantiter  = 0;
        for ($a = 0; $a < count($data); $a++) {
            if ($data[$a]->adressPv == $pv) {
                $quantiter = $data[$a]->qte;
            }
        }

        if (count($res) > 0 || $res[0]->etat_vente == 'Nom Vendu') {
            echo json_encode([
                'success' => $res[0]->etat_vente,
                'data' => $res,
                'qte' => $quantiter
            ]);
        } else {
            echo json_encode([
                'success' => 'qsdqsdsq',
                'data' => ''
            ]);
        }
    }



    public function panierAffiche($page = '')
    {
        $pv = $this->input->post('pv');
        if (isset($_SESSION['pv'])) {
            $pv = $_SESSION['pv'];
        }

        if ($pv == '') {
            $point_de_vente = $this->dispatch->get_the_pv();
        } else {
            $point_de_vente = $this->dispatch->get_the_pv($pv);
        }





        if (count($point_de_vente) == 0) {
            $pv_panier = '';
        } else {
            $pv_panier = $point_de_vente[0];
        }





        if (isset($_POST['recherche'])) {
            $keyword = strip_tags(trim($_POST['recherche']));
            if ($keyword == 'Recherche des Numeros :') {
                $keyword = '';
            }
        } else {
            $keyword  = '';
        }


        if ($page == '') {
            $page = 0;
        }

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }



        $iteration = 0;
        $data = [];
        $all = $this->stock->getAll();






        for ($i = 0; $i < count($all); $i++) {
            $qte = $this->stock->getQTE($all[$i]->refmateriel, $all[$i]->idPointVente);
            $exist = false;
            for ($k = 0; $k < count($data); $k++) {
                if (ucfirst($data[$k]->refmateriel) == ucfirst($all[$i]->refmateriel)) {
                    if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
                        $exist = true;
                    }
                }
            }
            if (!$exist) {
                $data[$iteration] = $all[$i];
                $data[$iteration]->qte = $qte[0]->qte;
                $iteration++;
            }
        }



        if ($keyword != '') {
            $recherche = [];
            for ($i = 0; $i < count($data); $i++) {
                if (strpos(ucfirst($data[$i]->refmateriel), ucfirst($keyword)) > -1 ||   strpos(ucfirst($data[$i]->designationmateriel), ucfirst($keyword)) > -1 ||  strpos(ucfirst($data[$i]->adressPv), ucfirst($keyword)) > -1) {
                    if (isset($_SESSION['pv'])) {
                        if ($data[$i]->idPointVente == $_SESSION['pv'])
                            $recherche[] = $data[$i];
                    } else {
                        $recherche[] = $data[$i];
                    }
                }
            }
        }




        $temp = [];
        if ($pv_panier != '' && $keyword != '') {
            for ($i = 0; $i < count($recherche); $i++) {
                if ($recherche[$i]->idPointVente == $pv_panier->idPointVente) {
                    $temp[] = $recherche[$i];
                }
            }
            $recherche = $temp;
        } else if ($pv_panier != '') {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]->idPointVente == $pv_panier->idPointVente) {
                    $temp[] = $data[$i];
                }
            }
            $data = $temp;
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->numserie = $this->stock->getAllNumSerie($data[$i]->refmateriel, $data[$i]->idPointVente);
        }

        $temporairement = [];
        for ($i = 0; $i < count($data); $i++) {
            if (count($data[$i]->numserie) != 0) {
                $temporairement[] = $data[$i];
            }
        }
        $data = $temporairement;


        $dispatch = $this->stock->dispatch();
        for ($v = 0; $v < count($dispatch); $v++) {
            for ($i = 0; $i < count($data); $i++) {
                if (ucfirst($data[$i]->refmateriel) == ucfirst($dispatch[$v]->refmateriel) && $data[$i]->idPointVente == $dispatch[$v]->idPointVente) {
                    $data[$i]->qte -= $dispatch[$v]->qteProduit;
                }
            }
        }




        // $sousP = $this->appro->getSousPr($date, $ref);

        if ($keyword == "") {
            $nbr = count($data);
        } else if ($pv_panier == '') {
            $nbr = count($recherche);
        }

        $stock = [];


        if ($keyword == '') {
            for ($i =  $start; $i < $start + PAGINATION; $i++) {
                if ($data[$i] != '') {
                    if (isset($_SESSION['pv'])) {
                        $pv = $_SESSION['pv'];
                        if ($data[$i]->idPointVente == $pv) {
                            $stock[] = $data[$i];
                        }
                    } else {
                        if ($data[$i]->idPointVente ==  $pv_panier->idPointVente) {
                            $stock[] = $data[$i];
                        }
                    }
                }
            }
        } else {
            for ($i =  $start; $i < $start + PAGINATION; $i++) {
                if (count($recherche) != 0) {
                    if ($recherche[$i] != null)
                        $stock[] = $recherche[$i];
                } else {
                    $stock = $data;
                }
            }
        }




        if ($keyword != '') {
            $temporaire = [];
            if (count($recherche) == 0) {
                for ($i = 0; $i < count($stock); $i++) {
                    foreach ($stock[$i]->numserie as $num) {
                        if ($num->numero_serie == $keyword) {
                            $temporaire[] = $stock[$i];
                        }
                    }
                }
                $stock = $temporaire;
            }
        }

        for ($i = 0; $i < count($stock); $i++) {
            $prix = $this->dispatch->getPrixPanier($stock[$i]->refmateriel);
            $stock[$i]->prix_unitaire = $prix->prixProduit;
        }

        if ($nbr <= PAGINATION) {
            echo json_encode([
                'data' => $stock,
                'page' => $page,
                'pagin' => 'Non',
                'nbr' => $nbr,
                'pv' => $point_de_vente,
                'idpv' => $pv_panier->idPointVente
            ]);
        } else {
            $nbr_data = ceil($nbr / PAGINATION);
            echo json_encode([
                'data' => $stock,
                'page' => $page,
                'pagin' => 'oui',
                'nbr' => $nbr_data,
                'pv' => $point_de_vente,
                'idpv' => $pv_panier->idPointVente
            ]);
        }
    }



    // ******************** UTILE ***************************** //
    public function index()
    {
        $this->jail();
        // $this->jail() ; 
        $data['title'] = 'Dispatch';
        $data['css'] = 'dispatch.css';
        $js['js'] = 'dispatch.js';

        $pv = $this->dispatch->getAllVente();

        $prod = $this->dispatch->getAllProd();

        // $modes = $this->mode->AllMode() ; 




        // * pagination * // 

        $config = array();
        $config["base_url"] = base_url() . 'dispatch';
        $config["total_rows"] = $this->client->get_count();
        $config["per_page"] = PAGINATION;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        if (isset($_GET['page'])) {
            $this->session->set_flashdata('vente_client', 'ok');
        }

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['client'] = $this->client->get_authors($config["per_page"], $start);


        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);


        if ($this->session->userdata('show_stock_alert')) {
            $this->session->unset_userdata('show_stock_alert');
            $appros = $this->stock->getAll_seuil();
            $stocks = $this->stock->getStock($appros);

            $all_pv = $this->stock->pv_stock();

            // convertire 
            foreach ($stocks as $key => $stock) {
                $unites = $stock->unites;
                $min_qte = $stock->stock;
                // donner les qte correspondant a chaque unite
                $unite_convert = $this->covertion($unites, $min_qte);
                $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
            }


            $data_to_send =   [
                'pv' => $pv,
                'prod' => $prod,
                // 'modes' => $modes , 
                'data' => $datapag,
                'time_alert' => true
            ];

            if (count($stocks) > 0) {
                $data_to_send['stock_alerts'] = $stocks;
            }
            $this->load->view('dispatch', $data_to_send);
        } else {
            $this->load->view('dispatch', [
                'pv' => $pv,
                'prod' => $prod,
                // 'modes' => $modes , 
                'data' => $datapag
            ]);
        }

        $this->load->view('templates/footer', $js);
    }

    public function getMode()
    {
        // $modes = $this->mode->AllMode() ; 

        // echo json_encode( [
        //     'mode' => $modes 
        // ]) ; 
    }


    public function getStock()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST["idmateriel"] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }
        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST["id_pv"] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }

        $quantite =  $this->dispatch->getStock($idmateriel, $id_pv);

        if ($quantite > 0) {
            echo json_encode([
                'success' => true,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }


    public function recheche_prix()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }
        $prix = $this->dispatch->getPrixUnite($idmateriel);




        // verification du quantiter disponnible 
        // $quantite =  $this->dispatch->getStock($idmateriel, $id_pv);

        if (count($prix) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prix[0],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $prix,
            ]);
        }
    }

    /**
     * Prendre le stock d'un produit par id et point de vente 
     *
     * @return void
     */
    public function getStock_json()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }

        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }

        // verification du quantiter disponnible 
        $quantite =  $this->dispatch->getStock($idmateriel, $id_pv);


        echo json_encode([
            'success' => true,
            'quantite' => $quantite,
        ]);

    }
    public function getStock__()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }

        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }


        $prixUnites = $this->dispatch->getPrixUnite($idmateriel);




        // verification du quantiter disponnible 
        $quantite =  $this->dispatch->getStock($idmateriel, $id_pv);

        echo '<pre>';
        var_dump($prixUnites);
        echo '</pre>';
        die;
        if (count($prixUnites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        }
    }

    public function unite()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }


        $unites = $this->dispatch->unites($idmateriel);

        if (count($unites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $unites,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function tiquet($reference)
    {
        $info = $this->liste->getAllInfo($reference);
        $idfacture = '';
        if (count($info) > 0) {
            $idfacture = $info[0]->idfacture;
        }

        $tous = $this->liste->getFacture($idfacture);

        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


        $total['ht'] = $tous->montant_payer;
        $total['ht'] += $tous->frais;
        $TVA = $total['ht'] * 20 / 100;
        $total['tva'] = $TVA;
        $total['ttc'] = $TVA + $total['ht'];

        $this->load->library('Pdftiquet');

        $pdf = new Pdftiquet("P", "mm", [80, 100]);

        $pdf->set_admin($admin);
        $pdf->set_facture($tous);
        $pdf->set_total($total);
        $pdf->AddPage();

        // Appeler la méthode Body pour ajouter le contenu principal
        $pdf->Body();

        $pdf->Output();



        // $this->load->view('tiquet', [
        //     'facture' => $tous,
        //     'total' => $total,
        //     'admin' => $admin
        // ]);


    }

    public function facture($fact)
    {
        $info = $this->liste->getAllInfo($fact);
        $idfacture = '';
        if (count($info) > 0) {
            $idfacture = $info[0]->idfacture;
        }

        $fact = $this->liste->getFacture($idfacture);

        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


        $total['ht'] = $fact->montant_payer;
        $total['ht'] += $fact->frais;
        $TVA = $total['ht'] * 20 / 100;
        $total['tva'] = $TVA;
        $total['ttc'] = $TVA + $total['ht'];


        $this->load->library('Facturevente');


        $pdf = new Facturevente("P", "mm", "A4");
        $pdf->AddPage();


        for ($i = 0; $i < $pdf->nbr_page; $i++) {
            $offest = $i * 10;
            $limite = ($i + 1) * 10;
            if ($pdf->nbr_page > 1 && $i != ($pdf->nbr_page - 1)) {
                $pdf->pos_foot = 158;
                $pdf->affiche = false;
            } else {
                $pdf->pos_foot = 100;
                $pdf->affiche = true;
            }

            $pdf->head($fact, $admin);
            $pdf->Ln(8);
            $pdf->corps($fact, $total, $offest,  $limite);
            $pdf->Ln($pdf->pos_foot);
            // $pdf->foot();

            $pdf->pos_foot = 100;
        }
        $pdf->Output();
    }


    // ******************** UTILE ***************************** //
}

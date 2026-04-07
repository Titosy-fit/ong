 <?php

    use PhpParser\Node\Expr\PostDec;

    defined('BASEPATH') or exit('No direct script access allowed');

    class Materiel extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model("MaterielModel", "materiel");
            $this->load->model('PrixModel', 'p_model');
            $this->load->model('UniteModel', 'unite');
        }

        public function index()
        {

            $page = isset($_GET['page']) ? $_GET['page'] : 0;
            $datapag["links"] = $this->pagination('materiel', $this->materiel->get_count());
            if ((int)$page == 0) {
                $start = (int)$page * PAGINATION;
            } else {
                $start = ((int)$page - 1) * PAGINATION;
            }
            $datapag['materiel'] = $this->materiel->get_authors(PAGINATION, $start);

            // echo '<pre>' ;
            // var_dump( $datapag ) ; 
            // echo '</pre>' ; die  ; 


            // * pagination * // 
            $this->jail();
            $data['title'] = 'Produit';
            $data['css'] = 'materiel.css';
            $js['js'] = 'materiel.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('materiel', [
                'data' => $datapag
            ]);
            $this->load->view('templates/footer', $js);
        }

        // enregistrement 
        public function register()
        {
            $reference = '';
            if (isset($_POST['referenceMat']) && $_POST['referenceMat'] != '') {
                $reference = ucfirst(strip_tags(trim($_POST['referenceMat'])));
            }

            $designationmateriel = '';
            if (isset($_POST['designationMat']) && $_POST['designationMat'] != '') {
                $designationmateriel = strip_tags(trim($_POST['designationMat']));
            }
            $type = '';
            if (isset($_POST['type']) && $_POST['type'] != '') {
                $type = strip_tags(trim($_POST['type']));
            }
            $fiche = '';
            if (isset($_POST['fiche']) && $_POST['fiche'] != '') {
                $fiche = strip_tags(trim($_POST['fiche']));
            }
            $seuil = '';
            if (isset($_POST['seuil']) && $_POST['seuil'] != '') {
                $seuil = strip_tags(trim($_POST['seuil']));
            }

            // unite specify 
            $seuil_min = '';
            if (isset($_POST['seuil_min']) && $_POST['seuil_min'] != '') {
                $seuil_min =  trim(strip_tags($_POST['seuil_min']));
            }
            $identification_seul = '';
            if (isset($_POST['seul_unite']) && $_POST['seul_unite'] != '') {
                $identification_seul =  trim(strip_tags($_POST['seul_unite']));
            }
            $unite_parent = '';
            if (isset($_POST['unite_parent']) && $_POST['unite_parent'] != '') {
                $unite_parent =  trim(strip_tags($_POST['unite_parent']));
            }

            $ref = $this->materiel->verifRefProd($reference);

            if (count($ref) > 0) {
                $this->session->set_flashdata('designationmateriel', 'Ajout réussie');
            } else {
                $data_insert = [
                    "refmateriel" => $reference,
                    "designationmateriel" => $designationmateriel,
                    "typemateriel" => $type,
                    "fiche" => $fiche,
                    "seuil" => $seuil,
                    'seuil_min' => $seuil_min,
                    'idadmin' => $_SESSION['idadmin'],
                ];

                $idmateriel = $this->materiel->insertProduit($data_insert);

                $idunite_seuil = 0;
                if ($unite_parent != '') {
                    $idunite =  $this->materiel->insertUnite([
                        'idmateriel' => $idmateriel,
                        'denomination' => $unite_parent,
                        'idadmin' => $_SESSION['idadmin'],
                    ]);
                    $idunite_seuil = $idunite;
                }

                if (isset($_POST['sousUnite']) && isset($_POST['formule'])) {
                    $sous_unites = $_POST['sousUnite'];
                    $formules = $_POST['formule'];

                    for ($i = 0; $i < count($sous_unites); $i++) {
                        $denomination = $sous_unites[$i];
                        if ($denomination != '') {
                            $formule = $formules[$i];
                            $idunite =  $this->materiel->insertUnite([
                                'idmateriel' => $idmateriel,
                                'denomination' => $denomination,
                                'idparent' => $idunite,
                                'formule' => $formule,
                                'idadmin' => $_SESSION['idadmin'],
                            ]);


                            if ($i == ($identification_seul - 1)) {
                                $idunite_seuil = $idunite;
                            }
                        }
                    }
                }

                // ajouter l'idunite du seuli d'alert dans le materiel inserer
                $this->materiel->updateUniteseuil($idmateriel,  $idunite_seuil);

                // $this->session->set_userdata('produit_add', 'reussi');
                $this->session->set_flashdata('produit_add', true);
            }
            redirect('materiel');
        }

        // Effacer
        public function deleteProd()
        {
            // ici
            $id = $this->input->post('id');
            $this->jail();

            $this->unite->deleteByproduit($id);


            // Produit
            $this->materiel->deleteProduit($id);


            $this->session->set_userdata('delete', 'Erreur de l\'ajout');

            echo json_encode([
                'success' => true,
                'exist' => false,
            ]);
        }
        public function DonnerProduit()
        {
            $id = $this->input->post('idmateriel');
            $data = $this->materiel->getProduitById($id);


            // verification s'i deje utilise
            $is_used = $this->materiel->is_used($id);


            if (count($data) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $data[0],
                    'is_used' => $is_used
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                ]);
            }
        }

        public function editProd()
        {
            $id = $this->input->post('id');

            $reference = '';
            if ($this->input->post('reference_modif') != null) {
                $reference = strip_tags(trim($this->input->post('reference_modif')));
            }
            $designationmateriel = '';
            if ($this->input->post('designation_modif') != null) {
                $designationmateriel = strip_tags(trim($this->input->post('designation_modif')));
            }

            $type = '';
            if ($this->input->post('type') != null) {
                $type = strip_tags(trim($this->input->post('type')));
            }
            $fiche = '';
            if ($this->input->post('fiche') != null) {
                $fiche = strip_tags(trim($this->input->post('fiche')));
            }

            // unite specify 
            $iduniter = $_POST['idunite'];

            $idunites = explode(',', $iduniter);
            $unite_parent = '';
            if (isset($_POST['unite_parent']) && $_POST['unite_parent'] != '') {
                $unite_parent = trim(strip_tags($_POST['unite_parent']));
            }
            $seuil_modif = '';
            if (isset($_POST['seuil_modif']) && $_POST['seuil_modif'] != '') {
                $seuil_modif =  trim(strip_tags($_POST['seuil_modif']));
            }
            $seuil_min_modif = '';
            if (isset($_POST['seuil_min_modif']) && $_POST['seuil_min_modif'] != '') {
                $seuil_min_modif =  trim(strip_tags($_POST['seuil_min_modif']));
            }
            $identification_seuil = '';
            if (isset($_POST['seul_unite_modif']) && $_POST['seul_unite_modif'] != '') {
                $identification_seuil =  trim(strip_tags($_POST['seul_unite_modif']));
            }




            $new_idunite = 0;
            if (isset($idunites[$identification_seuil])) {
                $new_idunite = $idunites[$identification_seuil];
            }
            $data = [];

            $data = [
                'refmateriel' => $reference,
                'designationmateriel' => $designationmateriel,
                'typemateriel' => $type,
                'fiche' => $fiche,
                'seuil' => $seuil_modif,
                'seuil_min' => $seuil_min_modif,
            ];


            // changer toutes les uniters du materiel modifer 

            // nbr des unites 
            $nbr_unites_init = count($idunites);
            // modifier l'unite parent 

            if (isset($idunites[0]) && $idunites[0] != '') {
                $this->unite->updateunite([
                    'denomination' => $unite_parent,
                ], $idunites[0]);
            } else if ($unite_parent != '') {
                $idParent = $this->materiel->insertUnite([
                    'idmateriel' => $id,
                    'denomination' => $unite_parent,
                    'idparent' => 0,
                    'formule' => 0,
                    'idadmin' => $_SESSION['idadmin'],
                ]);
            }


            $iteration = 1;


            if (isset($_POST['sousUnite']) && isset($_POST['formule'])) {

                // modifier les sous unites
                while (isset($_POST['sousUnite'][$iteration - 1]) && $_POST['sousUnite'][$iteration - 1] != '') {
                    $sous_unites = $_POST['sousUnite'];
                    $formules = $_POST['formule'];

                    if (isset($idunites[$iteration]) && $idunites[$iteration] != '') {
                        // on modifer 
                        $this->unite->updateunite([
                            'denomination' => $sous_unites[$iteration - 1],
                            'formule' => $formules[$iteration - 1],
                        ], $idunites[$iteration]);
                    } else {
                        // on ajoute 
                        // var_dump( $iteration ,  $identification_seuil ) ; die  ; 
                        $idParent = $this->unite->getIdlastUnite($id);
                        if (count($idParent) > 0) {
                            $idParent = $idParent[0]->idunite;

                            $idParent = $this->materiel->insertUnite([
                                'idmateriel' => $id,
                                'denomination' => trim(strip_tags($_POST['sousUnite'][$iteration - 1])),
                                'idparent' => $idParent,
                                'formule' => $formules[$iteration - 1],
                                'idadmin' => $_SESSION['idadmin'],
                            ]);

                            if ($iteration == $identification_seuil) {
                                $new_idunite = $idParent;
                            }
                        }
                    }
                    $iteration++;
                }
                if ($iteration   < $nbr_unites_init) {
                    // la modif a effacer des unités 
                    for ($i = $iteration; $i < count($idunites); $i++) {
                        $this->unite->deleteById($idunites[$i]);
                    }

                    // on efface aussi les prix s'il existe 
                    for ($i = $iteration; $i < count($idunites); $i++) {
                        $this->unite->deletePrix($idunites[$i]);
                    }
                }
            } else {
                for ($i = 1; $i < count($idunites); $i++) {
                    $this->unite->deleteById($idunites[$i]);
                }
            }

            // ajouter l'idunite du seuli d'alert dans le materiel inserer
            $this->materiel->updateUniteseuil($id,  $new_idunite);

            $this->materiel->updateProduit($id, $data);
            $this->session->set_flashdata('edit', 'fini');
            redirect('materiel');
        }

        public function verifProd()
        {
            $id = strip_tags(trim($this->input->post('id')));
            $reference = strip_tags(trim($this->input->post('reference')));

            $data = $this->materiel->verifyIfProdExiste($id, $reference);

            if (count($data) > 0) {
                $response['referenceExiste'] = true;
                $response['success'] = false;
            } else {
                $response = ['success' => true];
            }
            echo json_encode($response);

            // $tab = [];

            // for ($i = 0; $i < count($dataId); $i++) {
            //     array_push($tab, $dataId[$i]->refmateriel);
            //     array_push($tab, $dataId[$i]->designationmateriel);
            // }


            // if (in_array($reference, $tab)) {
            //     
            // }
            // if (in_array($designationmateriel, $tab)) {
            //     $response['designationExiste'] = true;
            //     $response['success'] = false;
            // }

        }

        public function recherche()
        {
            $keyword = trim(strip_tags($_GET['recherche']));

            $_POST['post'] = $keyword;


            $datapag["links"] =  $this->pagination_search('recherche',  count($this->materiel->verifRefProd($keyword)));
            $datapag["links"] = $this->pagination->create_links();

            $page = isset($_GET['page']) ? $_GET['page'] : 0;
            if ((int)$page == 0) {
                $start = (int)$page * PAGINATION;
            } else {
                $start = ((int)$page - 1) * PAGINATION;
            }
            $datapag['materiel'] = $this->materiel->verifRefProd($keyword, PAGINATION, $start);

            $data['title'] = 'Produit';
            $data['css'] = 'materiel.css';
            $js['js'] = 'materiel.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('materiel', [
                'data' => $datapag,
                'post' =>  $_POST['post']
            ]);
            $this->load->view('templates/footer', $js);
        }

        public function rechercherUniteProd()
        {
            $ref = $this->input->post('ref');
            $result = $this->materiel->rechercherUniteProd($ref);

            $data = [
                'data' => $result[0],
                'success' => true
            ];

            echo json_encode($data);
        }
        public function imprimer()
        {
            // echo '<pre>' ;
            $data = $this->materiel->imprimer();

            $this->load->view('imprim_produit', ['data' => $data]);
        }
    }

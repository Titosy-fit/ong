<?php

class Appro extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApproModel', 'appro');
        $this->load->model('PointVente_model', 'pv');
        $this->load->model('CodeModel', 'code');
        $this->load->model('UniteModel', 'unite');
        $this->load->model('FournisseurModel', 'fournisseur');
        $this->load->model('ExportationModel', 'exportation');


        // 
        $this->load->model('ProjetModel', 'projet');
    }
    /**
     * page principale  de approvisionnement
     *
     * @return void
     */
    public function index()
    {
        $page = 1;
        $this->jail();

        $nPages = ceil($this->appro->get_count() / PAGINATION);
        $current = 1;

        $datas = $this->appro->get_authors($page);

        // echo '<pre>' ;
        // var_dump( $datas ) ; 
        // echo '</pre>' ; die ; 

        $fournisseurs = $this->fournisseur->getAll();

        $data['title'] = 'Approvisionnement';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $pv = $this->pv->getAllPv();
        $projets = $this->projet->getpojet();
        $mat = $this->appro->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $datas,
            'pv' => $pv,
            'projets' => $projets,
            'mat' => $mat,
            'nPages' => $nPages,
            'fournisseurs' => $fournisseurs,
            'current' => 1
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function page($page = 1)
    {

        $this->jail();

        $nPages = ceil($this->appro->get_count() / PAGINATION);
        $current = 1;

        $datas = $this->appro->get_authors($page);

        $fournisseurs = $this->fournisseur->getAll();

        $data['title'] = 'Approvisionnement';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $pv = $this->pv->getAllPv();
        $projets = $this->projet->getpojet();
        $mat = $this->appro->getAllMat();



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $datas,
            'pv' => $pv,
            'projets' => $projets,
            'mat' => $mat,
            'nPages' => $nPages,
            'fournisseurs' => $fournisseurs,
            'current' => $page
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * recherche dans approvisionnement
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

        $recherche = $this->appro->searchDate($page,  $date_debut, $date_fin, $mot);

        $nPages = ceil($this->appro->searchDate($page,  $date_debut, $date_fin, $mot, true) / PAGINATION);
        $current = $page;

        $pv = $this->pv->getAllPv();
        $data['title'] = 'Appro';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $projets = $this->projet->getpojet();

        $mat = $this->appro->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ["appro" => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $recherche,
            'pv' => $pv,
            'mat' => $mat,
            'nPages' => $nPages,
            'current' => $current,
            'projets' => $projets,
        ]);

        $this->load->view('templates/footer', $js);
    }


    /**
     * trouver un produit par reference et par point de vente 
     *
     * @return void
     */
    public function recherche_produit()
    {
        $ref = '';
        if (isset($_POST['ref']) && $_POST['ref'] != '') {
            $ref = htmlspecialchars(trim($_POST['ref']));
        }

        $type_mat = '';
        if (isset($_POST['type_mat']) && $_POST['type_mat'] != '') {
            $type_mat = htmlspecialchars(trim($_POST['type_mat']));
        }


        $materiel = $this->appro->getProduitByRef($ref, $type_mat);



        if (count($materiel)) {
            $unites = [];
            $unites = $this->unite->getUniteByIdProduit($materiel[0]->idmateriel);

            if ($type_mat != '') {
                if ($type_mat == $materiel[0]->typemateriel) {
                    $materiel = $materiel[0];
                    echo json_encode([
                        'success' => true,
                        'materiel' => $materiel,
                        'unites' => $unites,
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'data' => '',
                        'type_err' => 'type'
                    ]);
                }
            } else {
                $materiel = $materiel[0];
                echo json_encode([
                    'success' => true,
                    'materiel' => $materiel,
                    'unites' => $unites,
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'data' => '',
                'type_err' => ''
            ]);
        }
    }




    /**
     * Enregistrement de l'approvisionnement
     *
     * @return void
     */
    public function registerAppro()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($this->input->post('idmateriel')));
        }

        $pv = '';
        if (isset($_POST['pv']) && $_POST['pv'] != '') {
            $pv = trim(strip_tags($this->input->post('pv')));
        }
        $projet = '';
        if (isset($_POST['projet']) && $_POST['projet'] != '') {
            $projet = trim(strip_tags($this->input->post('projet')));
        }

        $quantite = 1;
        if (isset($_POST['quantite']) && $_POST['quantite'] != '') {
            $quantite = trim(strip_tags($_POST['quantite']));
        }
        $prix = 0;
        if (isset($_POST['prix']) && $_POST['prix'] != '') {
            $prix = trim(strip_tags($_POST['prix']));
        }

        $idunite = '';
        if (isset($_POST['unite']) && $_POST['unite'] != '') {
            $idunite = trim(strip_tags($_POST['unite']));
        }

        $fournisseur = '';
        if (isset($_POST['fournisseur']) && $_POST['fournisseur'] != '') {
            $fournisseur = trim(strip_tags($_POST['fournisseur']));
        }

        // convertion on l'unite le plus petit 
        $min_qte = $this->convertion($idunite, $quantite);

        if (empty($pv)) {
            $this->session->set_flashdata('adrres', 'Ajout réussie');
        } else {
            if ($pv != '' && $idmateriel != '') {
                $date = date("Y/m/d H:i:s");
                $data = [
                    'idmateriel' => $idmateriel,
                    'idPointVente' => $pv,
                    'dateAppro' => $date,
                    'idadmin' => $_SESSION['idadmin'],
                    "idprojet" => $projet,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prix,
                    'min_qte' => $min_qte,
                    'idunite' => $idunite,
                    'idfournisseur ' => $fournisseur,
                ];

                $idappro = $this->appro->insertAppro($data);

                // Enregistrement des budgets sélectionnés
                $idbudgets = $this->input->post('idbudget');
                if (!empty($idbudgets)) {
                    $this->load->model('ApproBudgetModel', 'appro_budget');
                    $ids = explode(',', $idbudgets);
                    $montant_total = $quantite * $prix;
                    foreach ($ids as $idbudget) {
                        $this->appro_budget->insert([
                            'idAppro' => $idappro,
                            'idbudget' => (int)$idbudget,
                            'montant_utilise' => $montant_total  // ou répartir
                        ]);
                    }
                }

                /**
                 * ajouter le prix de l'appro dans le depense 
                 */
                $this->appro->depenseAppro(($quantite * $prix), $pv, '', $idappro);

                $this->session->set_userdata('added', 'ok');
            } else {
                $this->session->set_flashdata('erreur', 'Veuillez vérifier les données que vous avez saisies.');
            }
        }
        redirect('appro');
    }

    public function cmregister()
    {
        $idPointVente = '';
        if (isset($_POST['idPointVente']) && $_POST['idPointVente'] != '') {
            $idPointVente = trim(strip_tags($_POST['idPointVente']));
        }
        $idcommande = '';
        if (isset($_POST['idcommande']) && $_POST['idcommande'] != '') {
            $idcommande = trim(strip_tags($_POST['idcommande']));
        }
        $montant_total = '';
        if (isset($_POST['montant_total']) && $_POST['montant_total'] != '') {
            $montant_total = trim(strip_tags($_POST['montant_total']));
        }
        $idfournisseur = '';
        if (isset($_POST['idfournisseur']) && $_POST['idfournisseur'] != '') {
            $idfournisseur = trim(strip_tags($_POST['idfournisseur']));
        }
        $frais = '';
        if (isset($_POST['frais']) && $_POST['frais'] != '') {
            $frais = trim(strip_tags($_POST['frais']));
        }

        $teste = $this->appro->teste_commande($idcommande);

        if (count($teste) == 0) {
            $this->appro->depenseAppro(($montant_total + $frais), $idPointVente, $idcommande);

            $datas = [];
            if (isset($_POST['datas'])) {
                $datas = $_POST['datas'];
            }

            foreach ($datas as $key => $data) {
                $datas[$key]["idPointVente"] = $idPointVente;
                $datas[$key]["idadmin"] = $_SESSION['idadmin'];
            }

            $this->appro->commande_recue($idcommande);
            $this->appro->insert_batch($datas);
        }

        $_POST["reception"] = true;

        $this->session->set_flashdata('added', 'ok');
        // echo json_encode([
        //     'success' => true 
        // ]) ; 
    }

    public function deleteAppro()
    {
        $id = $this->input->post('idappro');

        $data = $this->appro->deleteAppro($id);
        $this->appro->deleteDepense($id);

        $this->session->set_flashdata('delete', 'Ajout réussie');

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

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


    public function verifyNumserie()
    {
        $numero = '';
        if (isset($_POST['numero']) && $_POST['numero'] != '') {
            $numero = trim(strip_tags($_POST['numero']));
        }
        $data = [];
        if ($numero != '') {
            $data = $this->appro->verifyNumserie($numero);
        }

        if (count($data) == 0 && $numero != '') {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }
    public function verifyImei()
    {
        $imei = '';
        if (isset($_POST['imei']) && $_POST['imei'] != '') {
            $imei = trim(strip_tags($_POST['imei']));
        }

        $data = [];
        if ($imei != '') {
            $data = $this->appro->verifyImei($imei);
        }

        if (count($data) == 0 && $imei != '') {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }


    public function getBudgetsByProjet_json()
    {
        $idprojet = $this->input->post('idprojet');
        $page = $this->input->post('page') ? (int)$this->input->post('page') : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $this->load->model('BudgetModel', 'budget');

        // Récupérer tous les budgets du projet
        $allBudgets = $this->db->select('b.*')
            ->from('budget b')
            ->where('b.idprojet', $idprojet)
            ->get()
            ->result();

        // Calcul du reliquat pour chaque budget
        foreach ($allBudgets as $budget) {
            $reste = $this->db->select("(
            b.budget - COALESCE(ld.total_depense, 0) - COALESCE(ld.total_return_liquidation, 0) - COALESCE(rr.total_return_relique, 0)
        ) AS reste", false)
                ->from('budget b')
                ->join("(
                SELECT am.idbudget, SUM(l.montant_depense) AS total_depense, SUM(l.montant_return) AS total_return_liquidation
                FROM avance_mission am JOIN liquidation l ON l.idmission = am.idmission GROUP BY am.idbudget
            ) ld", 'ld.idbudget = b.idbudget', 'left')
                ->join("(
                SELECT am.idbudget, SUM(r.montantReturn) AS total_return_relique
                FROM avance_mission am JOIN liquidation l ON l.idmission = am.idmission
                JOIN relique r ON r.idLiquidation = l.idliquidation GROUP BY am.idbudget
            ) rr", 'rr.idbudget = b.idbudget', 'left')
                ->where('b.idbudget', $budget->idbudget)
                ->get()->row();
            $budget->reste = $reste ? $reste->reste : $budget->budget;
        }

        $total = count($allBudgets);
        $paginatedBudgets = array_slice($allBudgets, $offset, $per_page);

        echo json_encode([
            'success' => true,
            'datas' => $paginatedBudgets,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]);
    }




    // ************ utile *************** //
}

<?php

use function PHPUnit\Framework\containsOnly;

class Transfert extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TransfertModel', 'transfert');
        $this->load->model('DispatchModel', 'dispatch');
        $this->load->model('ApproModel', 'appro');
    }
    /**
     * page pricipale pour transfert 
     *
     * @return void
     */
    public function index()
    {
        $this->jail();
        $assets['title'] = 'Transfert';
        $assets['css'] = 'transfert.css';
        $js['js'] = 'transfert.js';

        $pv = $this->dispatch->getAllVente();


        $datas = $this->transfert->getAuthor(1);

        // echo '<pre>' ;
        // var_dump( $datas ) ; 
        // echo "</pre>" ; die ; 

        $nPages = ceil(count($this->transfert->getAuthor())  / PAGINATION);
        $current = 1;

        // echo '<pre>' ;
        // var_dump( $nPages ) ; 
        // echo "</pre>" ; die ; 


        $this->load->view('templates/header', $assets);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);
        $this->load->view('transfert', [
            'pv' => $pv,
            'datas' => $datas,
            'nPages' => $nPages,
            'current' => 1
        ]);
        $this->load->view('templates/footer', $js);
    }
    /**
     * Pagination
     *
     * @param integer $page
     * @return void
     */
    public function page($page = 1)
    {
        $this->jail();
        $assets['title'] = 'Transfert';
        $assets['css'] = 'transfert.css';
        $js['js'] = 'transfert.js';

        $pv = $this->dispatch->getAllVente();


        $datas = $this->transfert->getAuthor($page);


        $nPages = ceil(count($this->transfert->getAuthor())  / PAGINATION);
        $current = $page;



        $this->load->view('templates/header', $assets);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);
        $this->load->view('transfert', [
            'pv' => $pv,
            'datas' => $datas,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * enregistrement
     *
     * @return void
     */
    public function register()
    {
        $this->jail();
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }
        $pv_source = '';
        if (isset($_POST['pv_source']) && $_POST['pv_source'] != '') {
            $pv_source = trim(strip_tags($_POST['pv_source']));
        }
        $pv_destination = '';
        if (isset($_POST['pv_destination']) && $_POST['pv_destination'] != '') {
            $pv_destination = trim(strip_tags($_POST['pv_destination']));
        }
        $unite = '';
        if (isset($_POST['unite']) && $_POST['unite'] != '') {
            $unite = trim(strip_tags($_POST['unite']));
        }
        $quantite = '';
        if (isset($_POST['quantite']) && $_POST['quantite'] != '') {
            $quantite = trim(strip_tags($_POST['quantite']));
        }
        $qte_min = '';
        if (isset($_POST['qte_min']) && $_POST['qte_min'] != '') {
            $qte_min = trim(strip_tags($_POST['qte_min']));
        }

        $data_transfert = [
            'idmateriel' => $idmateriel,
            'qunatite_transfert' => $quantite,
            'qte_min_transfert' => $qte_min,
            'idunite' => $unite,
            'idPointVente_source' => $pv_source,
            'idPointVente_destination' => $pv_destination,
            'idadmin' => $_SESSION['idadmin']
        ];
        $data_appro = [
            'idmateriel' => $idmateriel,
            'quantite' => $quantite,
            'min_qte' => $qte_min,
            'idunite' => $unite,
            'idPointVente' => $pv_source,
            'idPointVente_destination' => $pv_destination,
            'idadmin' => $_SESSION['idadmin']
        ];



        $this->transfert->register($data_transfert);

        $this->session->set_flashdata('success', 'ajout reusi');
        redirect('transfert');
    }


    /**
     * suppression
     *
     * @return void
     */
    public function delete()
    {
        $idtransfert = '';
        if (isset($_POST['idtransfert']) &&  $_POST['idtransfert'] != '') {
            $idtransfert = trim(strip_tags($_POST['idtransfert']));
        }

        $data = $this->transfert->getByid($idtransfert);
        if (count($data) > 0) {
            $data = $data[0];
            if (!$data->reception_transfert) {
                $this->transfert->delete($idtransfert);
                $this->session->set_flashdata('deleted', true);
                echo json_encode([
                    'success' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false
                ]);
            }
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }

    /**
     * Reception
     *
     * @return void
     */
    public function receive()
    {
        $idtransfert = '';
        if (isset($_POST['idtransfert']) &&  $_POST['idtransfert'] != '') {
            $idtransfert = trim(strip_tags($_POST['idtransfert']));
        }


        $data = $this->transfert->getByid($idtransfert);
        if (count($data) > 0) {
            $data = $data[0];


            $data_appro = [
                'idmateriel' => $data->idmateriel,
                'quantite' => $data->qunatite_transfert,
                'min_qte' => $data->qte_min_transfert,
                'idunite' => $data->idunite,
                'idPointVente' => $data->idPointVente_destination,
                'idadmin' => $_SESSION['idadmin'],
                'idtransfert' => $data->idtransfert,
            ];

            if (!$data->reception_transfert) {
                $this->appro->insertAppro($data_appro);
                $this->transfert->receive($idtransfert);
                echo json_encode([
                    'success' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false
                ]);
            }
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }


    /**
     * Recherche dans transfert
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
        // if ($heure_debut == '' && $date_debut != '')
        //   $heure_debut = '00:00:00';
        // else 
        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;
        // if ($heure_fin == '' && $date_fin != '')
        //   $heure_fin = '00:00:00';
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


        $mot = strip_tags(trim($_POST['recherche']));
        $etat = strip_tags(trim($_POST['etat']));

        $_POST['mot'] = $mot;
        $_POST['etat'] = $etat;



        $this->jail();
        $assets['title'] = 'Transfert';
        $assets['css'] = 'transfert.css';
        $js['js'] = 'transfert.js';

        $pv = $this->dispatch->getAllVente();


        $datas = $this->transfert->search($date_debut, $date_fin, $mot, $etat, $page);

        // echo '<pre>' ;
        // var_dump( $datas ) ; 
        // echo "</pre>" ; die ; 

        $nPages = ceil(count($this->transfert->search($date_debut, $date_fin, $mot, $etat))  / PAGINATION);
        $current = $page;

        // echo '<pre>' ;
        // var_dump( $nPages ) ; 
        // echo "</pre>" ; die ; 


        $this->load->view('templates/header', $assets);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);
        $this->load->view('transfert', [
            'pv' => $pv,
            'datas' => $datas,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $js);
    }
}

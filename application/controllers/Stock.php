<?php

use SebastianBergmann\CodeCoverage\Util\Percentage;

defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('StockModel', 'stock');
        $this->load->model('MaterielModel', 'materiel');
        $this->load->model('ExportationModel', 'exportation');
    }

    /**
     * function principale 
     *
     * @return void
     */
    public function index()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $iteration = 0;
        // $data = [];
        $appros = $this->stock->getAll($page);
        // echo '<pre>' ;
        //     var_dump( $appros ) ; 
        //     echo '</pre>' ; die ; 
        $stocks = $this->stock->getStock($appros);

        $all_pv = $this->stock->pv_stock();
        // var_dump('eee') ; die ; 

        // convertire 
        foreach ($stocks as $key => $stock) {
            $unites = $stock->unites;
            $min_qte = $stock->stock;
            // donner les qte correspondant a chaque unite
            $unite_convert = $this->covertion($unites, $min_qte);
            $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        }


        $lien = $this->pagination('stock', count($this->stock->getAll()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * principale seuil
     *
     * @return void
     */
    public function seuil()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $iteration = 0;
        // $data = [];
        $appros = $this->stock->getAll_seuil($page);
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



        $lien = $this->pagination('stock', count($this->stock->getAll_seuil()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Recherche stock
     *
     * @return void
     */
    public function search()
    {
        $keyword = '';
        if (isset($_GET['recherche']) && $_GET['recherche'] != '') {
            $keyword = strip_tags(trim($_GET['recherche']));
        }
        $_POST['post'] = $keyword;


        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_search($keyword,  $page);
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

        $lien = $this->pagination_search('Stock/search', count($this->stock->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * recherche seuil 
     *
     * @return void
     */
    public function search_seuil()
    {
        $keyword = '';
        if (isset($_GET['recherche']) && $_GET['recherche'] != '') {
            $keyword = strip_tags(trim($_GET['recherche']));
        }
        $_POST['post'] = $keyword;


        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_search_seuil($keyword,  $page);
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

        $lien = $this->pagination_search('Stock/search', count($this->stock->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * filtre
     *
     * @return void
     */
    public function filtre()
    {
        $filter = [];
        $type = '';

        $order = [
            'asc' => 'desc',
            'desc' => 'asc'
        ];


        if (isset($_GET['type']) && $_GET['type'] != '') {
            $type = trim(strip_tags($_GET['type']));
        }

        $pv = [];
        if (isset($_GET['filter'])) {
            $pv = $this->input->get('filter');
            $filter[$type] = $pv;
        }

        $design = '';
        if (isset($_GET['design'])) {
            $design = $_GET['design'];
            $filter[$type] = $design;
            $_POST[$type] = $order[$design];
        }
        $ref = '';
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            $filter[$type] = $ref;
            $_POST[$type] = $order[$ref];
        }

        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_filtre($type, $filter,  $page);
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

        $lien = $this->pagination_search('filtre', count($this->stock->getAll_filtre($type, $filter)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * filtre seuil 
     *
     * @return void
     */
    public function filtre_seuil()
    {
        $filter = [];
        $type = '';

        $order = [
            'asc' => 'desc',
            'desc' => 'asc'
        ];


        if (isset($_GET['type']) && $_GET['type'] != '') {
            $type = trim(strip_tags($_GET['type']));
        }

        $pv = [];
        if (isset($_GET['filter'])) {
            $pv = $this->input->get('filter');
            $filter[$type] = $pv;
        }

        $design = '';
        if (isset($_GET['design'])) {
            $design = $_GET['design'];
            $filter[$type] = $design;
            $_POST[$type] = $order[$design];
        }
        $ref = '';
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            $filter[$type] = $ref;
            $_POST[$type] = $order[$ref];
        }

        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_filtre_seuil($type, $filter,  $page);
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

        $lien = $this->pagination_search('filtre', count($this->stock->getAll_filtre($type, $filter)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    // public function getSousPr($page = '')
    // {
    //     if ($page == '') {
    //         $page = 0;
    //     }

    //     $pagin = 3;


    //     $date = trim(strip_tags($_POST['date']));
    //     $ref = trim(strip_tags($_POST['ref']));

    //     $pv = '';
    //     if (isset($_POST['pv'])) {
    //         $pv = $_POST['pv'];
    //     }

    //     $sousP = $this->stock->getSousPr($date, $ref, $pv);

    //     $nbr = count($sousP);




    //     if ((int)$page == 0) {
    //         $start = (int)$page * $pagin;
    //     } else {
    //         $start = ((int)$page - 1) * $pagin;
    //     }

    //     if ($nbr <= $pagin) {
    //         echo json_encode([
    //             'data' => $sousP,
    //             'page' => $page,
    //             'pagin' => 'Non',
    //             'nbr' => $nbr
    //         ]);
    //     } else {

    //         $sousPaginer = $this->stock->getSousPrMPaginer($ref, $date, $pagin, $start, $pv);
    //         $nbr_data = ceil($nbr / $pagin);
    //         echo json_encode([
    //             'data' => $sousPaginer,
    //             'soup' => $sousP,
    //             'page' => $page,
    //             'pagin' => 'oui',
    //             'nbr' => $nbr_data
    //         ]);
    //     }
    // }
    // public function imprimer()
    // {
    //     $appros = $this->stock->getAll();
    //     $stocks = $this->stock->getStock($appros);

    //     $all_pv = $this->stock->pv_stock();


    //     // convertire 
    //     foreach ($stocks as $key => $stock) {
    //         $unites = $stock->unites;
    //         $min_qte = $stock->stock;
    //         // donner les qte correspondant a chaque unite
    //         $unite_convert = $this->covertion($unites, $min_qte);
    //         $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
    //     }

    //     // echo '<pre>' ;
    //     // var_dump( $stocks ) ; 
    //     // echo '</pre>' ; die  ; 


    //     $this->load->view('imprim_stock', ['data' =>  $stocks]);
    // }
}

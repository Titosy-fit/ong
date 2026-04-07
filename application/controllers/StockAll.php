<?php
class StockAll extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('StockAllModel', 'stockall');
    }

    public function index()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $iteration = 0;
        // $data = [];
        $appros = $this->stockall->getAll($page);
        // echo '<pre>' ;
        //     var_dump( $appros ) ; 
        //     echo '</pre>' ; die ; 
        $stocks = $this->stockall->getStock($appros);

        $all_pv = $this->stockall->pv_stock();
        // var_dump('eee') ; die ; 


        // convertire 
        foreach ($stocks as $key => $stock) {
            $unites = $stock->unites;
            $min_qte = $stock->stock;
            // donner les qte correspondant a chaque unite
            $unite_convert = $this->covertion($unites, $min_qte);
            $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        }

        // echo "<pre>"; 
        // var_dump( $stocks ) ; 
        // echo '</pre>' ; die  ; 

        $lien = $this->pagination('stock', count($this->stockall->getAll()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockall', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

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

        $appros = $this->stockall->getAll_search($keyword,  $page);
        $stocks = $this->stockall->getStock($appros);
        $all_pv = $this->stockall->pv_stock();


        // convertire 
        foreach ($stocks as $key => $stock) {
            $unites = $stock->unites;
            $min_qte = $stock->stock;
            // donner les qte correspondant a chaque unite
            $unite_convert = $this->covertion($unites, $min_qte);
            $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        }

        $lien = $this->pagination_search('Stock/search', count($this->stockall->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockall', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }
}

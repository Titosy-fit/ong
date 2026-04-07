<?php
class Emploi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('EmploiModel', 'emploi');
    }


    public function index($lan = 'fr')
    {
        $data['title'] = 'Mode d\'emploi';
        $data['css'] = 'emploi.css';
        $js['js'] = 'emploi.js';

        if ($lan == 'fr') {
            $tutos  = $this->emploi->getvideofr();
        } else {
            $tutos  = $this->emploi->getvideoml();
        }


        $nPages = ceil(count($tutos) / PAGINATION);

        $page = 1;
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $temp = [];
        for ($i = $start; $i < $start + PAGINATION; $i++) {
            if (isset($tutos[$i])) {
                $temp[] = $tutos[$i];
            }
        }
        $tutos = $temp;


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['client' => true]);
        $this->load->view('templates/tete');
        if ($lan == 'fr') {
            $this->load->view('emploi',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => 1
            ]);
        } else {
            $this->load->view('emploiml',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => 1
            ]);
        }
        $this->load->view('templates/footer', $js);
    }
    public function page($page = 1,  $lan = 'fr')
    {
        $data['title'] = 'Mode d\'emploi';
        $data['css'] = 'emploi.css';
        $js['js'] = 'emploi.js';

        if ($lan == 'fr') {
            $tutos  = $this->emploi->getvideofr();
        } else {
            $tutos  = $this->emploi->getvideoml();
        }


        $nPages = ceil(count($tutos) / PAGINATION);

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $temp = [];
        for ($i = $start; $i < $start + PAGINATION; $i++) {
            if (isset($tutos[$i])) {
                $temp[] = $tutos[$i];
            }
        }
        $tutos = $temp;


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['client' => true]);
        $this->load->view('templates/tete');
        if ($lan == 'fr') {
            $this->load->view('emploi',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        } else {
            $this->load->view('emploiml',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        }
        $this->load->view('templates/footer', $js);
    }


    public function search($page  = 1, $lan = 'fr')
    {
        $query = trim(strip_tags($_POST['query']));

        $_POST['query'] = $query;

        $_POST['query'] = $query;
        if ($query != '') {
            $query = '%' . $query . '%';
        }




        if ($lan == 'fr') {
            $tutos = $this->emploi->getTutoFr_search($query);
        } else {

            $tutos = $this->emploi->getTutoMal_search($query);
        }


        $nPages = ceil(count($tutos) / PAGINATION);

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $temp = [];
        for ($i = $start; $i < $start + PAGINATION; $i++) {
            if (isset($tutos[$i])) {
                $temp[] = $tutos[$i];
            }
        }
        $tutos = $temp;

        $data['title'] = 'Mode d\'emploi';
        $data['css'] = 'emploi.css';
        $js['js'] = 'emploi.js';

        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['client' => true]);
        $this->load->view('templates/tete');
        if ($lan == 'fr') {
            $this->load->view('emploi',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        } else {
            $this->load->view('emploiml',  [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        }
        $this->load->view('templates/footer', $js);

        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }
}

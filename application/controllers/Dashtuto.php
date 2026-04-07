<?php

use function PHPUnit\Framework\containsOnly;

class Dashtuto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdministrateurModel', 'admin');
    }

    public function jail()
    {
        if (!isset($_SESSION['user'])) {
            redirect('admin');
        }
    }

    public function index()
    {
        $this->jail();
        // Malagasy 
        $data['title'] = 'Admin';
        $data['css'] = 'dashtuto.css';
        $js['js'] = 'dashtuto.js';

        $tutos = $this->admin->getAllTutoMal();

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
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('dashtuto', [
            'tutos' => $tutos,
            'nPages' => $nPages,
            'current' => 1,
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }



    public function modifier($lan = '')
    {

        // $config['upload_path']   = './public/upload/videos/';
        // $config['allowed_types'] = 'mp4|avi|mov|mkv';
        // $config['max_size']      = '100000'; // Taille maximale en Ko
        // $config['encrypt_name']  = FALSE; // Utiliser le nom original du fichier

        // Enregistrer les informations dans la base de données
        $titre = $this->input->post('titre');
        $numero = $this->input->post('numero');
        $langue = $this->input->post('langue');
        $video = $this->input->post('video');
        $id = $this->input->post('id');


        $langue = ($langue == '1') ? 'Malagasy' : 'Français';






        // $this->upload->initialize($config);

        if ( $video != '') {
            // Fichier téléchargé avec succès
            // $upload_data = $this->upload->data();
            // $video_name = $upload_data['file_name']; // Nom du fichier original ou renommé
            // $video_path = 'public/upload/videos/' . $video_name; // Chemin relatif à stocker en base de données

            // Enregistrer les informations dans la base de données





            $data = [
                'titre'  => $titre,
                'video'  => $video,
                'numero'  => $numero,
                'langue'  => $langue,
            ];
            $this->admin->updatetuto($data, $id);
        } else {
            $data = [
                'titre'  => $titre,
                'numero'  => $numero,
                'langue'  => $langue,
            ];
            $this->admin->updatetuto($data, $id);
        }

        // Message de succès
        $this->session->set_flashdata('update', true);

        // Rediriger vers la page d'administration



        if ($lan == 'fr') {
            redirect('Admin/tutoFr');
        } else {
            redirect('Admin/tuto');
        }
    }


    public function page($page = 1, $lan = '')
    {
        // Malagasy 
        $data['title'] = 'Admin';
        $data['css'] = 'dashtuto.css';
        $js['js'] = 'dashtuto.js';

        if ($lan == 'fr') {
            $tutos = $this->admin->getAllTutoFr();
        } else {

            $tutos = $this->admin->getAllTutoMal();
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
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');

        if ($lan == 'fr') {
            $this->load->view('dashtutofr', [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        } else {
            $this->load->view('dashtuto', [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        }

        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }


    public function register()
    {
        $titre = $this->input->post('titre');
        $langue = $this->input->post('langue');
        $video = $this->input->post('video');

        // Convertir la langue si nécessaire
        $langue = ($langue == 1) ? 'Malagasy' : 'Français';

        $numerot = $this->admin->getLastVideo($langue);
        $numerot = (count($numerot) == 0) ? 1 : $numerot[0]->numero + 1;

        $data = [
            'titre'  => $titre,
            'langue' => $langue,
            'video'  => $video,
            'numero' => $numerot,
        ];
        $this->admin->save($data);

        // Message de succès
        $this->session->set_flashdata('success', true);

        // Rediriger vers la page d'administration
        $this->redirectBasedOnLanguage($lan = '');
    }
    // public function register()
    // {
    //     // Chemin du répertoire de téléchargement
    //     $upload_path = FCPATH . 'public/upload/videos/';

    //     // Assurez-vous que le répertoire existe
    //     if (!is_dir($upload_path)) {
    //         mkdir($upload_path, 0755, true); // Crée le répertoire si nécessaire
    //     }

    //     // Nom du fichier temporaire
    //     $file_temp = $_FILES['video']['tmp_name'];
    //     // Nom du fichier à sauvegarder
    //     $file_name = $_FILES['video']['name'];
    //     // Chemin complet du fichier de destination
    //     $file_path = $upload_path . $file_name;

    //     // Vérifiez si le fichier a bien été uploadé
    //     if (move_uploaded_file($file_temp, $file_path)) {
    //         // Fichier déplacé avec succès
    //         $video_path = 'public/upload/videos/' . $file_name;

    //         // Enregistrer les informations dans la base de données
    //         $titre = $this->input->post('titre');
    //         $langue = $this->input->post('langue');

    //         // Convertir la langue si nécessaire
    //         $langue = ($langue == 1) ? 'Malagasy' : 'Français';

    //         $numerot = $this->admin->getLastVideo($langue);
    //         $numerot = (count($numerot) == 0) ? 1 : $numerot[0]->numero + 1;

    //         $data = [
    //             'titre'  => $titre,
    //             'langue' => $langue,
    //             'video'  => $video_path,
    //             'numero' => $numerot,
    //         ];
    //         $this->admin->save($data);

    //         // Message de succès
    //         $this->session->set_flashdata('success', true);

    //         // Rediriger vers la page d'administration
    //         $this->redirectBasedOnLanguage($lan);
    //     } else {
    //         // Erreur lors du déplacement du fichier
    //         $this->session->set_flashdata('error', 'Le téléchargement du fichier a échoué.');

    //         // Debugging (enlever pour la production)
    //         echo '<pre>';
    //         var_dump(error_get_last());
    //         echo '</pre>';
    //         die;

    //         // Rediriger vers la page d'administration
    //         $this->redirectBasedOnLanguage($lan);
    //     }
    // }

    private function redirectBasedOnLanguage($lan)
    {
        if ($lan == 'fr') {
            redirect('Admin/tutoFr');
        } else {
            redirect('Admin/tuto');
        }
    }


    public function  delete()
    {
        $id = trim(strip_tags($_POST['id']));

        $this->admin->delete($id);

        $this->session->set_flashdata('deleted', true);

        echo json_encode(['success' => true]);
    }


    public function search($page  = 1, $lan = '')
    {
        $query = trim(strip_tags($_POST['query']));

        $_POST['query'] = $query;
        if ($query != '') {
            $query = '%' . $query . '%';
        }


        // Malagasy 
        $data['title'] = 'Admin';
        $data['css'] = 'dashtuto.css';
        $js['js'] = 'dashtuto.js';

        $tutos = $this->admin->getAllTuto_search($query);



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
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');

        if ($lan == 'fr') {
            $this->load->view('dashtutofr', [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        } else {
            $this->load->view('dashtuto', [
                'tutos' => $tutos,
                'nPages' => $nPages,
                'current' => $page
            ]);
        }

        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }
}

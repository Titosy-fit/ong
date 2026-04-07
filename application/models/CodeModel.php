<?php

class CodeModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProduit($page = '')
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else if ($page != '') {
            $realOffset = (int)($page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('materiel')
            ->where('idadmin', $_SESSION['idadmin']);
        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }
    public function getAllProduit_num($page = '')
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else if ($page != '') {
            $realOffset = (int)($page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('appro')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->where('appro.numero <>', '')
            ->group_by('materiel.idmateriel')
            ->where('appro.idadmin', $_SESSION['idadmin']);

        if (isset($_SESSION['pv']))
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }
    public function getAllProduit_imprim_num($page, $ref, $limit)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = (int)$page * $limit;
        } else if ($page != '') {
            $realOffset = (int)($page - 1) * $limit;
        }

        $this->db->select('*')
            ->from('appro')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->where('appro.numero <>', '')
            ->where('materiel.refmateriel', $ref)
            ->where('appro.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv']))
            $this->db->where('appro.idPointVente', $_SESSION['pv']);

        if ($page != '') {
            $this->db->limit($limit, $realOffset);
        }
        return $this->db->get()->result();
    }
    public function getAllProduit_imprim($page = '', $limit = 0)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = (int)$page * $limit;
        } else if ($page != '') {
            $realOffset = (int)($page - 1) * $limit;
        }

        $this->db->select('*')
            ->from('materiel')
            ->where('idadmin', $_SESSION['idadmin']);
        if ($page != '') {
            $this->db->limit($limit, $realOffset);
        }
        return $this->db->get()->result();
    }



    public function getAllProduit_search($keyword,  $page = '')
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else if ($page != '') {
            $realOffset = (int)($page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('materiel')
            ->where('idadmin', $_SESSION['idadmin']);

        if ($keyword != '') {
            $this->db->like('refmateriel', $keyword);
            $this->db->or_like('designationmateriel', $keyword);
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }
}

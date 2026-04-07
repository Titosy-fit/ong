<?php
class BeneficiaireModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // verification 
    public function verify($telbene = '', $numcinbene = '', $idbeneficiaire = '')
    {
        $this->db->select('*')
            ->from('beneficiaire')
            ->where('idadmin', $_SESSION['idadmin']);
        if ($idbeneficiaire != '') {
            $this->db->where('idbeneficiaire <>', $idbeneficiaire);
        }
        
        $this->db->where('telbene', $telbene) ; 

        $this->db->or_where('numcinbene' , $numcinbene  ) ; 

        return $this->db->get()->result();
    }


    // enregistrement 
    public function insert($data)
    {
        $this->db->insert('beneficiaire', $data);
        return $this->db->insert_id();
    }

    // les données 
    public function getAutor($page = '')
    {
        // LIMITE 
        if ((int)$page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else {
            $realOffset = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('beneficiaire b')
            ->join('user', 'user.idUser = b.idUser', 'left')
            ->where('b.idadmin', $_SESSION['idadmin']);

        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->order_by('b.idbeneficiaire' , "desc")->get()->result() ; 
    }

    // get beneficiaire par id
    public function donner( $idbeneficiaire ){
        return $this->db->select('*')
                    ->from('beneficiaire')
                    ->where('idadmin' , $_SESSION['idadmin'])
                    ->where('idbeneficiaire' , $idbeneficiaire )
                    ->get()->result() ; 
    }

    // delete 
    public function delete( $idbeneficiaire ){
        $this->db->where('idbeneficiaire' , $idbeneficiaire )  
                    ->where('idadmin' , $_SESSION['idadmin'])
                    ->delete('beneficiaire') ; 
    }

    // modification 
    public function updatebene( $data , $idbeneficiaire  ){
        $this->db->where('idbeneficiaire' , $idbeneficiaire )
                ->where('idadmin' , $_SESSION['idadmin'])
                ->update('beneficiaire' , $data ) ; 
    }

    // recherche 
    public function search( $keyword = '' , $page = ''){
        if ((int)$page == 0) {
            $start = (int)$page * (int)PAGINATION ;
        } else {
            $start = ((int)$page - 1) * (int)PAGINATION ;
        }

        $this->db->select('*')
                ->from('beneficiaire b')
                ->join('user u' , 'b.idUser = u.idUser' , 'left') ; 

        if ( $keyword != ''){
            $this->db->like('b.nombene' , $keyword ) ; 
            $this->db->or_like('b.prenombene' , $keyword ) ; 
            $this->db->or_like('b.mailbene' , $keyword ) ; 
            $this->db->or_like('b.telbene' , $keyword ) ; 
            $this->db->or_like('b.numcinbene' , $keyword ) ; 
            $this->db->or_like('b.adressebene' , $keyword ) ; 
            $this->db->or_like('u.nomUser' , $keyword ) ; 
        }

        if ( $page != ''){
            $this->db->limit( PAGINATION , $start ) ; 
        }

        return $this->db->order_by('b.idbeneficiaire')
                    ->get()->result() ; 
    }
}

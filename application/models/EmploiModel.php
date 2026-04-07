<?php 
class EmploiModel extends CI_Model 
{
    public function __construct( )
    {
        parent::__construct() ;
    }

    public function getvideofr ( ){
        return $this->db->select('*')
                ->from('tutoriel')
                ->where('langue' , 'français')
                ->order_by('numero' , 'asc')
                ->get()->result() ; 
    }
    public function getvideoml ( ){
        return $this->db->select('*')
                ->from('tutoriel')
                ->where('langue' , 'Malagasy')
                ->order_by('numero' , 'asc')
                ->get()->result() ; 
    }



    public function getTutoFr_search( $query = '' ){
        $this->db->select('*')
        ->from('tutoriel')
        ->where('langue' ,'Français') ; 

        if ( $query != ''){
            $this->db->where('titre like' , $query ) ;
            $this->db->or_where('numero like' , $query ) ;
        }


        $data = $this->db->order_by('numero' , 'asc')
        ->get()->result() ; 
        return $data ;  
    }
    public function getTutoMal_search( $query = '' ){
        $this->db->select('*')
        ->from('tutoriel')
        ->where('langue' ,'Malagasy') ; 


        if ( $query != ''){
            $this->db->where('titre like' , $query ) ;
            $this->db->or_where('numero like' , $query ) ;
        }


        $data = $this->db->order_by('numero' , 'asc')
        ->get()->result() ; 
        return $data ;  
    }



}
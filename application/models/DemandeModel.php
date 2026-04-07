<?php 
class DemandeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct() ; 
    }

    public function getByfact( $facture = ''){
        $demandes =  $this->db->select('dispatch.idfacture , dispatch.Facture , panier.quantite , panier.idPanier , materiel.*  ,unite.idunite , unite.denomination')
                ->from('dispatch')
                ->join('panier' , 'panier.idfacture = dispatch.idfacture' , 'left')
                ->join('materiel' , 'materiel.idmateriel = panier.idmateriel' , 'left')
                ->join('unite' , 'unite.idunite = panier.idunite' , 'left')
                ->where('panier.isreturn' , '0' )
                ->where('dispatch.Facture' , $facture )
                ->where('dispatch.idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
        return $demandes  ; 
    }
    
}
<?php

class ListeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllPv()
    {
        return $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
    }

    public function getAllListe($page = '' , $type_dispatch = 'vente') 
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $idadmin = $_SESSION['idadmin'];
        $this->db->select('*');
        $this->db->from('dispatch')
            ->join('pointvente', 'pointvente.idPointVente = dispatch.idPointVente', 'inner')
            ->join('beneficiaire', 'beneficiaire.idbeneficiaire = dispatch.idbeneficiaire', 'left')
            // ->join('modepaiement', 'modepaiement.idModePaiement  = dispatch.idModePaiement ', 'left')
            ->join('user', 'user.idUser  = dispatch.idUser ', 'left')
            ->join('(SELECT idUser as idagent ,  nomUser AS nomagent , contact AS telagent FROM user) agent' , 'agent.idagent = dispatch.idagent' , 'left')
            ->join('(SELECT idprojet  ,  codeprojet  FROM projet) projet' , 'projet.idprojet = dispatch.idprojet' , 'left')
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);

        if ( $type_dispatch != ''){
            $this->db->where('type_dispatch' , $type_dispatch ) ; 
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $this->db->order_by('dispatch.Facture', 'desc');

        $query = $this->db->get()->result();


        return $query;
    }

    public function getAllInfo($fac = '' , $type_dispatch = 'vente')
    {
        $q = $this->db->select('*')
            ->from('dispatch')
            ->where('Facture', $fac)
            ->where('idadmin', $_SESSION['idadmin']) 
            ->where('dispatch.type_dispatch' , $type_dispatch )
            ->get();
        return $q->result();
    }

    public function getTotal($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantTotal) as Tot FROM panier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $total[0]->Tot;
    }
    public function getApayer($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $totalP = $this->db->query("SELECT SUM(montantPaye) as TotP FROM panier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $totalP[0]->TotP;
    }

    public function getSumPrix($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(remise) as SommeR FROM panier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->SommeR;
    }

    public function getSumQte($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(qteProduit) as Qte FROM panier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->Qte;
    }

    public function getSumPayer($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantPaye) as payer FROM panier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        // var_dump ( $total[0]->payer ) ; die ; 
        return $total[0]->payer;
    }

    public function getFact($idfacture = '', $type_dispatch ='vente')
    {
        // LE VENTE 
        $ventes = $this->db->select('*')
            ->from('dispatch')
            ->join('user', 'user.idUser = dispatch.idUser', 'left')
            ->join('pointvente', 'pointvente.idPointVente  = dispatch.idPointVente', 'left')
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.idfacture', $idfacture)
            ->where('dispatch.type_dispatch' , $type_dispatch )
            ->get()->result();
        // ALL PANIER 
        $paniers = $this->db->select('*')
            ->from('panier')
            ->join('materiel', 'materiel.idmateriel = panier.idmateriel', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->join('unite', 'unite.idunite = panier.idunite', 'left')
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('panier.idfacture', $idfacture)
            ->get()->result();

        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->panier = $paniers;
            // prendre le benefiaire 
            return $ventes;
        } else {
            return [];
        }
    }

    public function getFacture($idfacture ='' , $type_dispatch = 'vente')
    {
        // $idadmin =  $_SESSION['idadmin'];
        // $data = $this->db->query("SELECT DISTINCT(idmateriel) FROM panier WHERE idfacture = '$idfacture'  AND idadmin = '$idadmin'")->result();


        // echo '<pre>' ;
        // var_dump( $data ) ; 
        // echo '</pre>' ; die ; 

        // $dataf = [];
        // for ($i = 0; $i < count($data); $i++) {
        //     $res =  $this->db->select('*')
        //         ->from('panier')
        //         ->join('materiel', 'materiel.idmateriel = panier.idmateriel', 'left')
        //         ->join('dispatch', 'dispatch.idfacture = panier.idfacture', 'left')
        //         ->join('beneficiaire', 'beneficiaire.idbeneficiaire = dispatch.idbeneficiaire', 'left')
        //         ->where('panier.idadmin', $_SESSION['idadmin'])
        //         ->where('materiel.idadmin', $_SESSION['idadmin'])
        //         ->where('dispatch.idadmin', $_SESSION['idadmin'])
        //         ->where('panier.idmateriel', $data[$i]->idmateriel)
        //         ->get()->result();
        //     $dataf[] = $res[0];
        // }

        // return $dataf;

        $ventes = $this->db->select('*')
            ->from('dispatch')
            ->join('pointvente' , 'pointvente.idPointVente = dispatch.idPointVente' , 'left')
            // ->join('modepaiement' , 'modepaiement.idModePaiement  = dispatch.idModePaiement ' , 'left')
            ->where('dispatch.idfacture', $idfacture)
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        $paniers =  $this->db->select('*')
            ->from('panier')
            ->join('materiel' , 'materiel.idmateriel = panier.idmateriel' , 'left')
            ->where('panier.idfacture', $idfacture)
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.type_dispatch', $type_dispatch)
            ->get()->result();
        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->paniers = $paniers;

            // beneficiaire 
            $beneficiaire = $this->db->select('*')
                            ->from('beneficiaire')
                            ->where('idadmin' , $_SESSION['idadmin'])
                            ->where('idbeneficiaire' , $ventes->idbeneficiaire )
                            ->get()->result() ;
            if ( count( $beneficiaire ) > 0 ){
                $beneficiaire = $beneficiaire[0] ; 
                foreach ($beneficiaire as $key => $value) {
                    $ventes->$key = $value ; 
                }

            }else {

            }

        }

        return $ventes;
    }


    public function getUser($id)
    {
        $res = $this->db->select('*')
            ->from('user')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idUser', $id)->get()->result();
        return $res[0];
    }


    public function getGrandTotal($refFacture)
    {
        $res = $this->db->select_sum('montantPaye')
            ->from('panier')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('Facture', $refFacture)
            ->get()->result();
        return $res;
    }

    public function getAllDetails($details = [] , $type_dispatch = 'vente')
    {
        $req = $this->db->select('*')
            ->from('panier')
            ->join('dispatch', 'dispatch.Facture = panier.Facture', 'left')
            ->join('materiel', 'materiel.refmateriel = panier.refmateriel', 'left')
            ->join('prix', 'prix.refmateriel = materiel.refmateriel', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.type_dispatch', $type_dispatch)
            ->where('panier.Facture', $details[0]->Facture)
            ->get();
        return $req->result();
    }

    public function getFactureSelected($details = [] , $type_dispatch = 'vente')
    {
        $req = $this->db->select('*')
            ->from('panier')
            ->join('dispatch', 'dispatch.Facture = panier.Facture', 'left')
            ->join('materiel', 'materiel.refmateriel = panier.refmateriel', 'left')
            ->join('prix', 'prix.refmateriel = materiel.refmateriel', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('dispatch.type_dispatch', $type_dispatch)
            ->where('panier.Facture', $details)
            ->get();
        return $req->result();
    }

    public function deleteVente($idfacture)
    {
        $this->db->where('idfacture', $idfacture)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('dispatch');
    }
    public function deletePanier($idfacture)
    {
        $this->db->where('idfacture ', $idfacture)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('panier');
    }

    public function search($debut = '', $fin = '', $mot = '', $lieu = 0, $page = '' , $type_dispatch = 'vente')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('dispatch')
            ->join('pointvente', 'pointvente.idPointVente = dispatch.idPointVente', 'inner')
            // ->join('modepaiement' , 'modepaiement.idModePaiement = dispatch.idModePaiement' , 'left')
            ->join('beneficiaire', 'beneficiaire.idbeneficiaire = dispatch.idbeneficiaire', 'left')
            ->join('user', 'user.idUser  = dispatch.idUser ', 'left')
            ->join('(SELECT idUser as idagent ,  nomUser AS nomagent , contact AS telagent FROM user) agent' , 'agent.idagent = dispatch.idagent' , 'left')
            ->join('(SELECT idprojet  ,  codeprojet  FROM projet) projet' , 'projet.idprojet = dispatch.idprojet' , 'left')
            ->where('dispatch.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);

            $this->db->where('dispatch.type_dispatch', $type_dispatch );
        if ($mot != '') {
            $this->db->like('dispatch.Facture', $mot);
            // $this->db->like('modepaiement.denom', $mot);
            $this->db->or_like('dispatch.idbeneficiaire', $mot);
            $this->db->or_like('beneficiaire.nombene', $mot);
            $this->db->or_like('beneficiaire.telbene', $mot);
            $this->db->or_like('projet.codeprojet', $mot);
            $this->db->or_like('agent.nomagent', $mot);
            $this->db->or_like('agent.telagent', $mot);
            $this->db->or_like('user.prenomUser', $mot);

            if( strpos( 'ADMIN' , strtoupper( $mot) ) > -1 ){
                $this->db->or_where('user.idUser' , null ) ; 
            }
        }

        if ($lieu != 0) {
            $this->db->where('pointvente.idPointVente ', $lieu);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('dispatch.dateVente LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('dispatch.dateVente like', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('dispatch.dateVente >=', $debut);
            $this->db->where('dispatch.dateVente <=', $fin);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('dispatch.Facture', 'desc')
            ->group_by('dispatch.Facture', 'desc');
        $query = $this->db->get()->result();

        return $query;
    }
}

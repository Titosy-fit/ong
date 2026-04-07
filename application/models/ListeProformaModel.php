<?php

class ListeProformaModel extends CI_Model
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

    public function getAllListe($page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $idadmin = $_SESSION['idadmin'];
        $this->db->select('*');
        $this->db->from('proforma')
            ->join('client', 'client.telClient = proforma.telClient', 'left')
            ->join('user', 'user.idUser  = proforma.idUser ', 'left')
            ->where('proforma.idadmin', $_SESSION['idadmin']) ; 
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $this->db->order_by('proforma.Facture', 'desc');

        $query = $this->db->get()->result();


        return $query;
    }

    public function getAllInfo($fac)
    {
        $q = $this->db->select('*')
            ->from('proforma')
            ->where('Facture', $fac)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $q->result();
    }

    public function getTotal($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantTotal) as Tot FROM prpanier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $total[0]->Tot;
    }
    public function getApayer($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $totalP = $this->db->query("SELECT SUM(montantPaye) as TotP FROM prpanier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $totalP[0]->TotP;
    }

    public function getSumPrix($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(remise) as SommeR FROM prpanier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->SommeR;
    }

    public function getSumQte($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(qteProduit) as Qte FROM prpanier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->Qte;
    }

    public function getSumPayer($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantPaye) as payer FROM prpanier WHERE Facture = '$fac' AND refmateriel = '$ref' AND idadmin = '$idadmin'")->result();
        // var_dump ( $total[0]->payer ) ; die ; 
        return $total[0]->payer;
    }

    public function getFact($idproforma)
    {
        // LE VENTE 
        $ventes = $this->db->select('*')
            ->from('proforma')
            ->join('user', 'user.idUser = proforma.idUser', 'left')
            ->join('pointvente', 'pointvente.idPointVente  = proforma.idPointVente', 'left')
            ->where('proforma.idadmin', $_SESSION['idadmin'])
            ->where('proforma.idproforma', $idproforma)
            ->get()->result();
        // ALL PANIER 
        $paniers = $this->db->select('*')
            ->from('prpanier')
            ->join('materiel', 'materiel.idmateriel = prpanier.idmateriel', 'left')
            ->where('prpanier.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('prpanier.idproforma', $idproforma)
            ->get()->result();

        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->prpanier = $paniers;
            return $ventes;
        } else {
            return [];
        }
    }

    public function getFacture($idproforma)
    {
        $ventes = $this->db->select('*')
            ->from('proforma')
            ->where('proforma.idproforma', $idproforma)
            ->where('proforma.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        $paniers =  $this->db->select('*')
            ->from('prpanier')
            ->join('materiel' , 'materiel.idmateriel = prpanier.idmateriel' , 'left')
            ->where('prpanier.idproforma', $idproforma)
            ->where('prpanier.idadmin', $_SESSION['idadmin'])
            ->get()->result();

        // echo '<pre>' ; 
        // var_dump( $idproforma ) ;  
        // echo '</pre>' ; die ; 
        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->paniers = $paniers;

            // client 
            $client = $this->db->select('*')
                            ->from('client')
                            ->where('idadmin' , $_SESSION['idadmin'])
                            ->where('telClient' , $ventes->telClient )
                            ->get()->result() ;
            if ( count( $client ) > 0 ){
                $client = $client[0] ; 
                foreach ($client as $key => $value) {
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
            ->from('prpanier')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('Facture', $refFacture)
            ->get()->result();
        return $res;
    }

    public function getAllDetails($details)
    {
        $req = $this->db->select('*')
            ->from('prpanier')
            ->join('proforma', 'proforma.Facture = prpanier.Facture', 'left')
            ->join('materiel', 'materiel.refmateriel = prpanier.refmateriel', 'left')
            ->join('prix', 'prix.refmateriel = materiel.refmateriel', 'left')
            ->where('prpanier.idadmin', $_SESSION['idadmin'])
            ->where('proforma.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('prpanier.Facture', $details[0]->Facture)
            ->get();
        return $req->result();
    }

    public function getFactureSelected($details)
    {
        $req = $this->db->select('*')
            ->from('prpanier')
            ->join('proforma', 'proforma.Facture = prpanier.Facture', 'left')
            ->join('materiel', 'materiel.refmateriel = prpanier.refmateriel', 'left')
            ->join('prix', 'prix.refmateriel = materiel.refmateriel', 'left')
            ->where('prpanier.idadmin', $_SESSION['idadmin'])
            ->where('proforma.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('prpanier.Facture', $details)
            ->get();
        return $req->result();
    }

    public function deleteVente($idproforma)
    {
        $this->db->where('idproforma', $idproforma)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('proforma');
    }
    public function deletePanier($idproforma)
    {
        $this->db->where('idproforma ', $idproforma)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('prpanier');
    }

    public function search($debut = '', $fin = '', $mot = '',  $page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('proforma')
            ->join('client', 'client.telClient = proforma.telClient', 'left')
            ->join('user', 'user.idUser  = proforma.idUser ', 'left')
            ->where('proforma.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->like('proforma.Facture', $mot);
            $this->db->or_like('proforma.telClient', $mot);
            $this->db->or_like('client.nomClient', $mot);
            $this->db->or_like('client.r_social', $mot);
            $this->db->or_like('user.prenomUser', $mot);

            if( strpos( 'ADMIN' , strtoupper( $mot) ) > -1 ){
                $this->db->or_where('user.idUser' , null ) ; 
            }
        }
   

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('proforma.dateVente LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('proforma.dateVente like', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('proforma.dateVente >=', $debut);
            $this->db->where('proforma.dateVente <=', $fin);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('proforma.Facture', 'desc')
            ->group_by('proforma.Facture', 'desc');
        $query = $this->db->get()->result();


        return $query;
    }
}

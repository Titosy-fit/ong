<?php

class MaterielModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // *  pagination 

    public function get_count()
    {
        $data = $this->db->select('*')
            ->from('materiel')
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
        return count($data);
    }

    public function get_authors($limit, $start)
    {
        $produits = $this->db->select('materiel.* , unite.idunite , unite.denomination ')
            ->from('materiel')
            ->join('unite', 'unite.idunite = materiel.idunite_seuil', 'left')
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('materiel.idmateriel', 'DESC')
            ->limit($limit, $start)
            ->get()->result();

        // Recuperation des unite
        foreach ($produits as $key => $materiel) {
            $id = $materiel->idmateriel;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idmateriel', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $materiel->unites = $unites;
        }


        return $produits;
    }

    public function getProduitExel()
    {
        $produits = $this->db->select('*')
            ->from('materiel')
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('idmateriel', 'asc')
            ->get()->result();

        foreach ($produits as $key => $materiel) {
            // selectionner les unite pour et ces prix
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idmateriel', $materiel->idmateriel)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();

            // if (count($unites) == 0) {
            //     // prix unique 
            //     $prix = $this->db->select('*')
            //         ->from('prix')
            //         ->where('idmateriel', $materiel->idmateriel)
            //         ->where('idadmin', $_SESSION['idadmin'])
            //         ->order_by('idPrix', 'desc')
            //         ->get()->result();


            //     if (count($prix) > 0) {
            //         $materiel->prix = [[
            //             "prix" =>  $prix[0]->prixProduit,
            //             "denomination" =>  '',
            //         ]];
            //     } else {
            //         $materiel->prix = [[
            //             "prix" =>  '',
            //             "denomination" =>  '',
            //         ]];
            //     }
            // } else {
            //     $materiel->prix = [];
            //     foreach ($unites as $key => $unite) {
            //         $prix_unite = $this->db->select('*')
            //             ->from('prix')
            //             ->where('idmateriel', $unite->idmateriel)
            //             ->where('idunite', $unite->idunite)
            //             ->where('idadmin', $_SESSION['idadmin'])
            //             ->order_by('idPrix', 'desc')
            //             ->get()->result();

            //         if (count($prix_unite) > 0) {
            //             $materiel->prix[] = [
            //                 "prix" => $prix_unite[0]->prixProduit,
            //                 "denomination" =>  $unite->denomination,
            //             ];
            //         } else {
            //             $materiel->prix = [[
            //                 "prix" =>  '',
            //                 "denomination" =>  '',
            //             ]];
            //         }
            //     }

            // }
        }
        return $produits;
    }



    // *  pagination 

    public function insertProduit($data)
    {
        $this->db->insert('materiel', $data);

        return $this->db->insert_id();
    }

    public function insertUnite($data)
    {
        $this->db->insert('unite', $data);
        return $this->db->insert_id();
    }

    public function verifRefProd($ref = '', $limit = '', $start = '')
    {
        $this->db->select('*')
            ->from('materiel')
            ->join('unite', 'unite.idunite = materiel.idunite_seuil', 'left')
            ->where('materiel.refmateriel LIKE', $ref)
            ->where('materiel.idadmin ', $_SESSION['idadmin'])
            ->or_where('materiel.designationmateriel LIKE', $ref);
        if ($limit == '') {
            return $this->db->get()->result();
        }

        $this->db->limit($limit, $start);
        $produits = $this->db->get()->result();

        foreach ($produits as $key => $materiel) {
            $id = $materiel->idmateriel;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idmateriel', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $materiel->unites = $unites;
        }

        return $produits;
    }

    public function verifDesProd($des)
    {
        $query = $this->db->select('*')
            ->from('materiel')
            ->where('designationmateriel', $des)
            ->get();
        return $query->result();
    }

    public function getAllProduit()
    {
        $query = $this->db->select('*')
            ->from('materiel')
            ->order_by('idmateriel', 'DESC')
            ->get();
        return $query->result();
    }

    public function getProduitById($id)
    {
        $produits  = $this->db->select('*')
            ->from('materiel')
            ->where('materiel.idmateriel', $id)
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('materiel.idmateriel', 'DESC')
            ->get()->result();
        // Recuperation des unites
        foreach ($produits as $key => $materiel) {
            $id = $materiel->idmateriel;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idmateriel', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $materiel->unites = $unites;
        }

        return $produits;
    }

    public function is_used($idmateriel)
    {
        // appro 
        $appros = $this->db->select('*')
            ->from('appro')
            ->where('idmateriel', $idmateriel)
            ->get()->result();
        if (count($appros) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduit($id)
    {
        /**
         * vente , demande et panier
         */
        $facture = $this->db->select('idfacture')
            ->from('panier')
            ->where('idmateriel', $id)
            
            ->get()->result();

        if (count($facture) > 0) {
            foreach ($facture as $key => $fact) {
                $idfact = $fact->idfacture;
                $this->db->where('idfacture', $idfact);
                $this->db->delete('dispatch');
            }
        } else {
            $facture = '';
        }
        $this->db->where('idmateriel', $id);
       
        $this->db->delete('panier');


        // commande 
        $cmfacture  = $this->db->select('idcommande')
            ->from('cmpanier')
            ->where('idmateriel', $id)
           
            ->get()->result();

        if (count($cmfacture) > 0) {
            foreach ($cmfacture  as $key => $fact) {
                $idfact = $fact->idcommande;
                $this->db->where('idcommande', $idfact);
              
                $this->db->delete('commande');
            }
        } else {
            $cmfacture  = '';
        }
        $this->db->where('idmateriel', $id);
       
        $this->db->delete('cmpanier');

        // transfert
        $this->db->where('idmateriel', $id)
            
            ->delete('transfert');


        $this->db->where('idmateriel', $id)->delete('appro');

        // retour 
       /* $this->db->where('idmateriel', $id)
            ->delete('remise');*/



        // Materiel 
        $this->db->where('idmateriel', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('materiel');
    }

    public function deleteAppro($id) {}

    public function updateProduit($id, $data)
    {
        $this->db->where('idmateriel', $id);
        $this->db->update('materiel', $data);
        return $this->db->affected_rows() >  0;
    }
    public function updateUniteseuil($idproduit,  $idunte)
    {
        $this->db->where('idmateriel', $idproduit)->update('materiel', ['idunite_seuil' => $idunte]);
    }

    public function verifyIfProdExiste($id, $ref)
    {
        // $idadmin = $_SESSION['idadmin'];
        // $data = $this->db->query("SELECT * FROM materiel  WHERE idmateriel <> '$id' AND idadmin = '$idadmin'")->result();
        // return $data;


        return $this->db->select('*')
            ->from('materiel')
            ->where('idmateriel <>', $id)
            ->where('refmateriel', $ref)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }

    public function lastProduit()
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM materiel WHERE idadmin = '$idadmin' ORDER BY idmateriel DESC")->result();
        return $data;
    }
    // * need 
    public function getProduitByRef($ref)
    {
        // $req = "SELECT * FROM ajoutproduit
        // INNER JOIN groups ON groups.id_group = ajoutproduit.id_group
        // WHERE refmateriel LIKE '%$ref%'";
        // return $this->db->query($req)->result();
        $data = $this->db->select('*')
            ->from('materiel ')
            ->where('refmateriel =', $ref)
            ->where('idadmin =', $_SESSION['idadmin'])
            ->get();
        return $data->result();
    }

    public function rechercherUniteProd($ref)
    {
        $data = $this->db->select('*')
            ->from('materiel')
            ->join('prix', 'prix.refmateriel = materiel.refmateriel ', 'left')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('materiel.refmateriel', $ref)
            ->where('prix.refmateriel', $ref)
            ->order_by('idPrix', 'DESC')
            ->get();
        return $data->result();
    }
    public function imprimer()
    {
        $query = $this->db->select('*')
            ->from('materiel')
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('idmateriel', 'DESC')
            ->get()->result();


        for ($i = 0; $i < count($query); $i++) {
            $ref = $query[$i]->refmateriel;
            $prix = $this->db->select('*')
                ->from('prix')
                ->where('refmateriel', $ref)
                ->where('idadmin', $_SESSION['idadmin'])
                ->order_by('idPrix ', 'desc')
                ->get()->result();
            $query[$i]->prix = $prix[0]->prixProduit;
        }
        return $query;
    }
}

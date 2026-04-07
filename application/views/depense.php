<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification du client</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Depense/edit') ?>" id="modifClient" method="post">
                                    <input type="hidden" id="iddepensemodif" name="id">
                                    <div class="mb-1">
                                        <label class="form-label">Raison :</label>
                                        <input name="raison_" id="raison_" type="text" class="form-control input_form-control">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Montant :</label>
                                        <input name="montant_" id="montant_" type="number" min='0' class="form-control input_form-control">
                                    </div>


                                    <div class="mt-2">
                                        <button type="button" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i>Modifier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if ($this->session->flashdata('edition')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php endif; ?>

                <!-- FIN MODAL -->


                <!-- Modal pour le demandeur  -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Choisissez le demandeur </h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="demandeur_modal" style="font-size: 14px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn d-none" id="select_demandeur" data-bs-toggle="modal" data-bs-target="#myModal">Demandeur</button>
                <!-- Modal pour le demandeur  -->



                <!-- ********************************** -->
                <!-- <div class="onglet">
                    <?php if (  $_SESSION['user_type'] == 'admin' ||  $_SESSION['type_of_user'] == 'Comptable'  ) : ?>
                        <a href="<?= base_url('liquidation') ?>" class="onglet_btn ">Liquidation</a>
                    <?php endif  ?>
                    <a href="<?= base_url('depense') ?>" class="onglet_btn active">Dépense approvisionnement</a>
                </div> -->


                <!-- <form action="<?= base_url('Depense/register') ?>" method="post">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Numéro OM : </label>
                            <input type="text" class="form-control" name="numero_om" id="numero_om" required>
                            <input type="text" class="form-control d-none" name="idmission" id="idmission" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Objet du mission : </label>
                            <input type="text" class="form-control" name="objet" id="objet" required readonly>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Titre de l'acticité : </label>
                        <input type="text" class="form-control" name="titre_activite" id="titre_activite" required readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nom de l'agent : </label>
                        <input type="text" class="form-control" name="nomagent" id="nomagent" required readonly>
                    </div>
                </form> -->


                <h5>Dépense approvisionnement</h5>

                <form action="<?= base_url('Depense/search') ?>" method="post" class="mb-4 " id="searchform">
                    <!-- <div class="row"> -->
                    <div class="group_form">
                        <label for="date_debut" class="form-label">Date de début : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control w-75" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                            <input type="time" class="form-control w-25" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label for="date_fin" class="form-label">Date de fin : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control w-75" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                            <input type="time" class="form-control w-25" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label for="type" class="form-label">Recherche : </label>
                        <div class="input-group  mb-3">
                            <input type="text" placeholder="Recherche" id="motclet" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label for="date_fin" class="form-label"> Dépôt : </label>
                        <div class="mb-3">
                            <select name="lieu" type="text" class="form-select" id="the_pv">
                                <option value="0">Lieu</option>
                                <?php foreach ($pv as $key => $pv_) : ?>
                                    <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="group_form btn_rechreche">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button class="btn btn-info" type="button" disabled>
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php else : ?>
                            <button class="btn btn-info" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php endif  ?>
                    </div>
                </form>

                <!-- <div class="money_container">
                    <div class="argent">
                        <div class="thearg " id="depense" style="background-color: rgb(255, 226, 229)">
                            <label class="form-label"><i class="fas fa-dollar-sign"></i> Dépense </label>
                            <p> <?= (isset($somme)) ? number_three($somme)  : '0 Ar' ?></p>
                        </div>
                    </div>
                </div> -->
                <div class="_tableau mt-4">
                    <table class="table">
                       <!-- Dans le <thead> : on change juste le titre de la colonne -->
<thead class="table-info">
    <tr>
        <th>Raison</th>
        <!-- Ancien : Titre de l'activite   →   Nouveau : Budget -->
        <th>Budget</th>
        <th>Demandeur</th>
        <th>N° BC</th>
        <th>Montant</th>
        <th>Date</th>
        <th> Dépôt </th>
        <th>Justification</th>
        <th>Editeur</th>
        <?php if ($_SESSION['user_type'] == 'admin') : ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>
</thead>

<!-- Dans le <tbody> : on remplace la cellule concernée -->
<tbody>
    <?php $depense = $data['depense'] ?>
    <?php foreach ($depense as $key => $dep) :  ?>
        <tr>
            <td><?= ($dep->raison == '') ? $dep->designation_budget : ucfirst($dep->raison) ?></td>
            
            <!-- Ligne modifiée : on affiche le budget à la place du titre d'activité -->
            <td><?= htmlspecialchars($dep->designation_budget ?? '--') ?></td>
            
            <td><?= ($dep->prenomagent != '') ? strtoupper($dep->nomagent) . ' ' . ucfirst($dep->prenomagent) : '--'  ?></td>
            <td><?= ($dep->cmfacture == '') ? '--' : $dep->cmfacture ?></td>
            <td><?= number_three($dep->montant) ?></td>
            <td><?= form_date($dep->datedepense) ?></td>
            <td><?= $dep->denomination_pv ?? '--' ?></td>
            <td>
                <?php if ($dep->justification) : ?>
                    <img style="width: 30px; height: 30px; object-fit: cover;"
                         src="<?= Myurl($dep->justification) ?>"
                         alt="Justification">
                <?php else : ?>
                    --
                <?php endif  ?>
            </td>
            <td><?= ($dep->prenomUser != '') ? strtoupper($dep->nomUser) . ' ' . ucfirst($dep->prenomUser) : 'Admin'  ?></td>
            <?php if ($_SESSION['user_type'] == 'admin') : ?>
                <td>
                    <button class="btn btn-danger delete" data-id="<?= $dep->iddepense ?>">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach ?>
</tbody>
                    </table>

                    <?php if ($nPages > 1) :  ?>
                        <div class="__pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center pagination-sm">
                                    <li class="page-item">
                                        <?php if ($current == 1) :  ?>
                                            <span class="page-link __disabled" aria-label="Previous">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                            </span>
                                        <?php else :  ?>
                                            <?php if (isset($_POST['date_debut'])) :  ?>

                                                <form action="<?= base_url('Depense/search/' . ($current - 1)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="group_form">
                                                            <label for="type" class="form-label">Recherche : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" id="motclet" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>

                                            <?php else :  ?>
                                                <a class="page-link" href='<?= base_url('Depense/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Depense/search/' . ($i)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="group_form">
                                                            <label for="type" class="form-label">Recherche : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" id="motclet" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Depense/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <li>
                                        <?php if ($current == $nPages) :  ?>
                                            <span class="page-link __disabled" aria-label="Next">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                            </span>
                                        <?php else :  ?>
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Depense/search/' . ($current +  1)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="group_form">
                                                            <label for="type" class="form-label">Recherche : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" id="motclet" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Depense/page/' . ($current + 1)) ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif;  ?>


                </div>
            </div>
        </div>
    </div>
</div>
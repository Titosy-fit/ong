<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- MODAL MODIFICATION -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('editPv') ?>" method="post" id="modifForm">
                                    <div class="mb-2">
                                        <label class="form-label">Code projet :</label>
                                        <input name="codeprojet-edit" id="codeprojet-edit" type="text" class="form-control input_form-control " required>
                                        <input name="projet-edit" id="projet-edit" type="text" class="form-control input_form-control d-none" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Titre du projet : </label>
                                        <input name="titreprojet-edit" id="titreprojet-edit" type="text" class="form-control input_form-control " readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lieu :</label>
                                        <input type="text" class="form-control " name="denomination" id="denomination_modif" required>
                                        <p class="text-danger d-none" id="msg-denomination-modif">Cette dénomination est déjà utilisée</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Adresse :</label>
                                        <input type="text" class="form-control" name="adresse_edit" id="adresse_edit" required>
                                        <input type="hidden" class="form-control" name="idPv" id="idPv">
                                        <p class="text-danger d-none" id="msg-adresse-modif">Cette adresse existe déjà</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact :</label>
                                        <input type="text" class="form-control " id="contact_edit" name="contact_edit" required>
                                        <p class="text-danger d-none" id="msg-num-modif">Ce numéro existe déjà</p>
                                    </div>
                                    <div class="_boutton">
                                        <button type="submit" id="modification" class="btn btn-info d-none">Modifier</button>
                                        <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Modifier</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Dépôt</h5>

                <?php if (can_manage('logistique')) : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('ajoutPV') ?>" method="post">
                    <?php endif; ?>
                        <div class="mb-2">
                            <label class="form-label">Code projet :</label>
                            <input name="codeprojet" id="codeprojet" type="text" class="form-control input_form-control " required>
                            <input name="projet" id="projet" type="text" class="form-control input_form-control d-none" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Titre du projet : </label>
                            <input name="titreprojet" id="titreprojet" type="text" class="form-control input_form-control " readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lieu :</label>
                            <input type="text" class="form-control " name="denomination" id="denomination" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse :</label>
                            <input type="text" class="form-control " name="adresse" id="adresse" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact :</label>
                            <input type="text" class="form-control " id="contact" name="contact" required>
                        </div>
                        <div class="_boutton">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                <button type="button" class="btn btn-info" disabled>
                                    <i class="fas fa-check"></i>
                                    Valider
                                </button>
                            <?php else : ?>
                                <button type="submit" class="btn btn-info" id="valider">
                                    <i class="fas fa-check"></i>
                                    <div class="spinner-wrapper d-none" id="spinner_validation">
                                        <div class="spinner-border"></div>
                                    </div>
                                    Valider
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if ($this->session->flashdata('adress')) : ?>
                    <script>
                        Myalert.erreur("Ce Dépôt existe déjà.")
                    </script>
                <?php elseif ($this->session->flashdata('ajout')) : ?>
                    <script>
                        Myalert.added()
                    </script>
                <?php elseif ($this->session->flashdata('edit')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php elseif ($this->session->flashdata('tel')) : ?>
                    <script>
                        Myalert.erreur("Ce contact Dépôt existe déjà.")
                    </script>
                <?php elseif ($this->session->flashdata('denomination')) : ?>
                    <script>
                        Myalert.erreur("Cette dénomination existe déjà.")
                    </script>
                <?php endif; ?>

                <?php if ($this->session->userdata('deletepv')) : ?>
                    <script>
                        Myalert.deleted()
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('deletepv') ?>

                <?php if (can_manage('logistique')) : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="get">
                    <?php else : ?>
                        <form action="<?= base_url('recherchePV') ?>" method="get">
                    <?php endif; ?>
                        <div class="input-group mt-4 mb-3">
                            <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php else : ?>
                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php else : ?>
                    <div class="input-group mt-4 mb-3">
                        <input name="recherche" type="text" class="form-control" placeholder="Recherche" disabled>
                        <button class="btn btn-info" type="button" disabled>
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Code projet</th>
                                <th>Titre du projet</th>
                                <th>Lieu</th>
                                <th>Adresse</th>
                                <th>Contact</th>
                                <?php if (can_manage('logistique')) : ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($pv)) : ?>
                                <?php
                                $pv = $data['lieu'];
                                for ($i = 0; $i < count($pv); $i++) : ?>
                                    <tr>
                                        <td><?= $pv[$i]->codeprojet ?></td>
                                        <td><?= $pv[$i]->titreprojet ?></td>
                                        <td><?= $pv[$i]->denomination_pv ?></td>
                                        <td><?= $pv[$i]->adressPv ?></td>
                                        <td><?= $pv[$i]->contactPv ?></td>
                                        <?php if (can_manage('logistique')) : ?>
                                            <td>
                                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                                    <button class="btn btn-danger" type="button" disabled>              
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning edit" disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button class="btn btn-danger delete" type="button" onclick="deleteIt(this)" data-id="<?= $pv[$i]->idPointVente; ?>">                 
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                    <button type="button" id="edit" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" onclick="Donneclient(this)" data-id="<?= $pv[$i]->idPointVente; ?>" data-contact="<?= $pv[$i]->contactPv ?>" data-adress="<?= $pv[$i]->adressPv ?>" data-denomination_pv="<?= $pv[$i]->denomination_pv ?>" data-codeprojet="<?= $pv[$i]->codeprojet ?>" data-titreprojet="<?= $pv[$i]->titreprojet ?>" data-idprojet="<?= $pv[$i]->idprojet ?>">                
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endfor ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->userdata('edit')) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Myalert.updated();
        });
    </script>
<?php endif; ?>
<?php $this->session->unset_userdata('edit') ?>

<?php if ($this->session->userdata('ajout')) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Myalert.added();
        });
    </script>
<?php endif; ?>
<?php $this->session->unset_userdata('ajout') ?>

<?php if ($this->session->userdata('existe')) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Myalert.erreur('Ce dépôt existe déjà')
        });
    </script>
<?php endif; ?>
<?php $this->session->unset_userdata('existe') ?>
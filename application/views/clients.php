<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification des clients</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('editClient') ?>" id="modifClient" method="post">
                                    <input type="hidden" name="idClient_modif" id="idClient_modif">
                                    <div id="private_modif">
                                        <div class="mb-1">
                                            <label class="form-label">Nom : </label>
                                            <input type="text" id="nom_modif" class="form-control" name="nom_modif">
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label">Prénom : </label>
                                            <input name="prenom_modif" id="prenom_modif" type="text" class="form-control input_form-control">
                                        </div>
                                    </div>
                                    <div id="public_modif">
                                        <div class="mb-2">
                                            <label class="form-label">Raison social : </label>
                                            <input name="r_social_modif" id="r_social_modif" type="tel" class="form-control input_form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Nif : </label>
                                            <input name="nif_modif" id="nif_modif" type="tel" class="form-control input_form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Stat : </label>
                                            <input name="stat_modif" id="stat_modif" type="tel" class="form-control input_form-control">
                                        </div>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Adresse : </label>
                                        <input name="adresse_modif" id="adresse_modif" type="text" class="form-control input_form-control">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Numéro Télephone : </label>
                                        <input name="numero_modif" id="numero_modif" type="tel" class="form-control input_form-control" required>
                                        <p class="text-danger d-none" id="msg-num-agents">Ce numéro existe déjà</p>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Email : </label>
                                        <input name="email_modif" type="email" id="email_modif" class="form-control input_form-control">
                                        <p class="text-danger d-none" id="msg-mail-agents">Cet email existe déjà</p>
                                    </div>


                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-info d-none" id="modification"><i class="fas fa-pen"></i> Modifier</button>
                                        <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pen"></i> Modifier</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($this->session->flashdata('success')) : ?>
                    <script>
                        Myalert.added()
                    </script>
                <?php elseif ($this->session->flashdata('edit')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php elseif ($this->session->flashdata('num')) : ?>
                    <script>
                        Myalert.erreur('Ce Numéro existe déjà.')
                    </script>
                <?php elseif ($this->session->flashdata('mail')) : ?>
                    <script>
                        Myalert.erreur('Cet email existe déjà.')
                    </script>
                <?php elseif ($this->session->flashdata('delete')) : ?>
                    <script>
                        Myalert.deleted()
                    </script>
                <?php endif; ?>
                <!-- FIN MODAL -->

                <h5 class="mb-3">Ajout de clients</h5>
                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('registerClient') ?>" method="post">
                        <?php endif; ?>
                        <div class="mb-2">
                            <label class="form-label">Type : </label>
                            <select class="form-select" id="client_type">
                                <option value="1">Particulier </option>
                                <option value="2">Entreprise </option>
                            </select>
                        </div>
                        <div class="mb-2 is_public d-none">
                            <label class="form-label">Raison social : </label>
                            <input name="r_social" type="tel" class="form-control input_form-control">
                        </div>
                        <div class="mb-2 not_public">
                            <label class="form-label ">Nom : </label>
                            <input name="nom" type="text" class="form-control input_form-control ">
                        </div>
                        <div class="mb-2 not_public">
                            <label class="form-label ">Prénom : </label>
                            <input name="prenom" type="text" class="form-control input_form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Adresse : </label>
                            <input name="adress" id="address" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Numéro Téléphone : </label>
                            <input name="numero" id="num" type="tel" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email : </label>
                            <input name="email" id="email" type="email" class="form-control input_form-control">
                            <div class="text-danger fs-5 font-weight-bold">
                                <?= form_error('email'); ?>
                            </div>
                        </div>

                        <div class="d-none is_public">
                            <div class="mb-2">
                                <label class="form-label">Nif : </label>
                                <input name="nif" type="tel" class="form-control input_form-control">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Stat : </label>
                                <input name="stat" type="tel" class="form-control input_form-control">
                            </div>

                        </div>
                        <div class="_boutton">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
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
                            <?php endif  ?>
                        </div>




                        </form>

                        <form action="<?= base_url('rechercheClient') ?>" method="get">
                            <div class="input-group mt-3 mb-3">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= (isset($query)) ? $query  :  ''; ?>">
                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
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


                        <div class="_tableau mt-4">
                            <table class="table table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Raison social </th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Adresse</th>
                                        <th>Numéro Télephone</th>
                                        <th>Email</th>
                                        <th>NIF </th>
                                        <th>STAT</th>
                                        <th>Editeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="search-results">
                                    <?php if (isset($data['client'])) :;
                                        $list = $data['client'];

                                    ?>
                                        <?php for ($i = 0; $i < count($list); $i++) :  ?>
                                            <tr>
                                                <td>
                                                    <?= ($list[$i]->r_social != '') ?  ucfirst($list[$i]->r_social) : ''; ?>
                                                </td>
                                                <td>
                                                    <?= ($list[$i]->nomClient != '') ? strtoupper($list[$i]->nomClient)  : ''; ?>
                                                </td>
                                                <td>
                                                    <?= ($list[$i]->prenomClient != '') ? ucfirst($list[$i]->prenomClient)  : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo $list[$i]->adresseClient; ?>
                                                </td>

                                                <td>
                                                    <?php echo $list[$i]->telClient; ?>
                                                </td>

                                                <td>
                                                    <?php echo $list[$i]->emailClient; ?>
                                                </td>

                                                <td>
                                                    <?= ($list[$i]->nif != '') ? $list[$i]->nif : ''; ?>
                                                </td>

                                                <td>
                                                    <?= ($list[$i]->stat != '') ? $list[$i]->stat : ''; ?>
                                                </td>
                                                <td>
                                                    <?= ($list[$i]->prenomUser != '') ? ucfirst($list[$i]->prenomUser) : 'Admin'; ?>
                                                </td>


                                                <td class="">
                                                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                        <button class="btn btn-danger" type="button" disabled >
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>

                                                        <button  type="button"  class="btn btn-warning" disabled>
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-danger delete" data-id="<?php echo $list[$i]->idClient; ?>" type="button" onclick="deleteIt(this)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>

                                                        <button data-id="<?php echo $list[$i]->idClient; ?>" type="button" id="edit" class="btn btn-warning edit" onclick="Donneclient(this)" data-toggle="modal" data-target="#editModal" data-id="<?php echo $list[$i]->idClient; ?>">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>


                                                    <?php $id = array('id' => 'form-' . $list[$i]->idClient, "class" => "delete", 'data-id' => $list[$i]->idClient);
                                                    echo form_open('delete', $id) ?>
                                                    <input type="hidden" name="client" id="client" value="<?php echo $list[$i]->idClient; ?>">
                                                    <?php echo form_close() ?>
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                        </div>
            </div>
        </div>
    </div>
</div>

<!-- <script async src="https : //pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7667839106330922" crossorigin="anonymous"></script> -->
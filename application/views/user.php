<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL MODIFICATION UTILISATEUR -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification de l'Utilisateur</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('editUser') ?>" id="modifClient" method="post">
                                    <div class="mb-1">
                                        <input type="hidden" name="id_modif" id="id-User">
                                        <input type="hidden" name="role_modif" id="hidden_role_modif" value="">
                                        <label class="form-label">Nom :</label>
                                        <input type="text" id="nom_modif" class="form-control" name="nom_modif" require>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Prénom :</label>
                                        <input name="prenom_modif" id="prenom_modif" type="text" class="form-control input_form-control" require>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Adresse :</label>
                                        <input name="adresse_modif" id="adresse_modif" type="text" class="form-control input_form-control" require>
                                    </div>

                                   <div class="mb-1">
    <label class="form-label">Numéro Télephone:</label>
    <input name="numero_modif" id="numero_modif" type="tel" class="form-control input_form-control" required>
    <p class="text-danger d-none" id="msg-num-modif">Ce numéro existe déjà</p>
</div>

<!-- NOUVEAU CHAMP CIN DANS LE MODAL -->
<div class="mb-1">
    <label class="form-label">Numéro CIN :</label>
    <input name="numero_cin_modif" id="numero_cin_modif" type="text" class="form-control input_form-control" required maxlength="20">
    <p class="text-danger d-none" id="msg-cin-modif">Ce CIN existe déjà</p>
</div>
                                    <div class="mb-2">
                                        <label class="form-label">Fonction :</label>
                                        <select name="poste" id="poste_modif" class="form-select">
                                        </select>

                                        
                                    </div>

                                      <div class="mb-2">
    <label class="form-label">Projet :</label>
    <select class="form-select" id="projetModif" name="idprojet_modif" required>
        <!-- Les options seront ajoutées dynamiquement par JavaScript -->
    </select>
</div>
                                    <!-- <div class="mb-2">
                                        <label class="form-label"> Dépôt :</label>
                                        <select class="form-select" id="pvModif" name="idPv_modif">

                                        </select>
                                    </div> -->

                                    <!-- RÔLES AFFICHÉS DIRECTEMENT POUR MODIFICATION -->
                                    <div class="mb-2">
                                        <label class="form-label">Rôles :</label>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox-modif" type="checkbox" value="projet" id="role_projet_modif">
                                            <label class="form-check-label" for="role_projet_modif">
                                                Projet
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox-modif" type="checkbox" value="budget" id="role_budget_modif">
                                            <label class="form-check-label" for="role_budget_modif">
                                                Budget
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox-modif" type="checkbox" value="liquidation" id="role_liquidation_modif">
                                            <label class="form-check-label" for="role_liquidation_modif">
                                                Liquidation
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox-modif" type="checkbox" value="logistique" id="role_logistique_modif">
                                            <label class="form-check-label" for="role_logistique_modif">
                                                Logistique
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox-modif" type="checkbox" value="consultation" id="role_consultation_modif">
                                            <label class="form-check-label" for="role_consultation_modif">
                                                Consultation
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Email:</label>
                                        <input name="email_modif" id="email_modif" type="email" class="form-control input_form-control" required>
                                        <p class="text-danger d-none" id="msg-mail-modif">Cet email existe déjà</p>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Modifier</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL MODIFICATION -->

                <div class="onglet">
                    <a href="<?= base_url('user') ?>" class="onglet_btn active">Ajout des utilisateurs</a>
                    <a href="<?= base_url('fonction') ?>" class="onglet_btn">Fonction</a>
                    <a href="<?= base_url('admin_inscription') ?>" class="onglet_btn">Ajouter administrateur</a>
                </div>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('registerUser') ?>" method="post">
                        <?php endif; ?>
                        <input type="hidden" name="role" id="hidden_role" value="">
                        <div class="mb-2">
                            <label class="form-label">Nom :</label>
                            <input name="nom" id="nom" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Prénom :</label>
                            <input name="prenom" id="prenom" type="text" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Adresse :</label>
                            <input name="adresse" id="adresse" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Poste :</label>
                            <?php if (isset($postes) && count($postes)) : ?>
                                <select name="poste" id="poste" class="form-select">
                                    <?php foreach ($postes as $key => $poste) :  ?>
                                        <option value="<?= $poste->idposte ?>"><?= $poste->designation_poste ?></option>
                                    <?php endforeach ?>
                                </select>
                            <?php else : ?>
                                <p class="text-danger">Aucun poste trouvé ...</p>
                            <?php endif ?>
                        </div>
                        <div class="mb-2">
                            <label class="form-label"> projet:</label>
                         <?php if (count($projets) > 0): ?>
        <select class="form-select" name="idprojet" required>
            <option value="">-- Choisir un projet --</option>
            <?php foreach ($projets as $projet): ?>
                <option value="<?= $projet->idprojet ?>">
                    <?= htmlspecialchars($projet->codeprojet) ?> — 
                    <?= htmlspecialchars($projet->titreprojet ?? 'Sans titre') ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <p class="text-danger">Aucun projet disponible. Créez-en un d'abord.</p>
    <?php endif; ?>
                        </div>
                        <!-- RÔLES AFFICHÉS DIRECTEMENT POUR AJOUT -->
                        <div class="mb-2">
                            <label class="form-label">Rôles :</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input role-checkbox" type="checkbox" value="projet" id="role_projet">
                                <label class="form-check-label" for="role_projet">
                                    Projet
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input role-checkbox" type="checkbox" value="budget" id="role_budget">
                                <label class="form-check-label" for="role_budget">
                                    Budget
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input role-checkbox" type="checkbox" value="liquidation" id="role_liquidation">
                                <label class="form-check-label" for="role_liquidation">
                                    Liquidation
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input role-checkbox" type="checkbox" value="logistique" id="role_logistique">
                                <label class="form-check-label" for="role_logistique">
                                    Logistique
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input role-checkbox" type="checkbox" value="consultation" id="role_consultation">
                                <label class="form-check-label" for="role_consultation">
                                    Consultation
                                </label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email :</label>
                            <input name="email" id="email" type="email" class="form-control input_form-control " required>
                        </div>
                       <div class="mb-2">
    <label class="form-label">Numéro Téléphone :</label>
    <input name="numero" id="numero" type="tel" class="form-control input_form-control" required>
    <p class="text-danger d-none" id="msg-numero">Ce numéro existe déjà</p>
</div>

<!-- NOUVEAU CHAMP CIN -->
<div class="mb-2">
    <label class="form-label">Numéro CIN :</label>
    <input name="numero_cin" id="numero_cin" type="text" class="form-control input_form-control" required maxlength="20">
    <p class="text-danger d-none" id="msg-cin">Ce CIN existe déjà</p>
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

                        <?php if ($this->session->flashdata('ajout')) : ?>
                            <script>
                                Myalert.added()
                            </script>
                        <?php elseif ($this->session->flashdata('edit')) : ?>
                            <script>
                                Myalert.updated()
                            </script>
                        <?php elseif ($this->session->flashdata('num')) : ?>
                            <script>
                                Myalert.erreur('Ce Numéro existe déjà')
                            </script>
                        <?php elseif ($this->session->flashdata('mail')) : ?>
                            <script>
                                Myalert.erreur('Ce mail est déjà utilisé par un autre utilisateur.')
                            </script>
                        <?php elseif ($this->session->flashdata('adrres')) : ?>
                            <script>
                                Myalert.updated('Veuillez inseret un   Dépôt ')
                            </script>
                        <?php elseif ($this->session->flashdata('delete')) : ?>
                            <script>
                                Myalert.deleted()
                            </script>
                        <?php elseif ($this->session->flashdata('effectuer')) : ?>
                            <script>
                                Myalert.deleted('Réinitialisation Réussi')
                            </script>
                        <?php endif; ?>
                        </form>
                        <form action="<?= base_url('rechercheUser'); ?>" method="get">
                            <div class="input-group mt-3 mb-3">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
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
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Adresse</th>
                                        <th>Poste</th>
                                        <th>Email</th>
                                        <th>Projet </th>
                                        <th>Numéro Télephone</th>
                                        <th>Rôles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php
                                    $user = $data['user'];
                                    for ($i = 0; $i < count($user); $i++) : ?>
                                        <tr>
                                            <td><?= $user[$i]->nomUser ?></td>

                                            <td><?= $user[$i]->prenomUser ?></td>

                                            <td><?= $user[$i]->adress ?></td>

                                            <td><?= $user[$i]->designation_poste ?></td>

                                            <td><?= $user[$i]->mail ?></td>

<td>
    <?php 
    if (!empty($user[$i]->codeprojet)) {
        echo html_escape($user[$i]->codeprojet);
        if (!empty($user[$i]->titreprojet)) {
            echo ' <small class="text-muted">(' . html_escape($user[$i]->titreprojet) . ')</small>';
        }
    } else {
        echo '<span class="text-muted">— aucun projet —</span>';
    }
    ?>
</td>
                                            <td><?= $user[$i]->contact ?></td>

                                            <td>
                                                <?php 
                                                if (isset($user[$i]->roles) && !empty($user[$i]->roles)) {
                                                    $roles = is_array($user[$i]->roles) ? $user[$i]->roles : explode(',', $user[$i]->roles);
                                                    echo implode(', ', $roles);
                                                } else {
                                                    echo 'Aucun rôle';
                                                }
                                                ?>
                                            </td>

                                            <td>

                                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning " disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-primary" disabled>
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button class="btn btn-danger delete" type="button" onclick="deleteIt(this)" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" id="edit" class="btn btn-warning edit" onclick="DonnerUser(this)" data-toggle="modal" data-target="#editModal" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                    <button type="button" id="reinitialize" class="btn btn-primary edit" onclick="reinitialize(this)" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                        </div>
            </div>
        </div>
    </div>
</div>
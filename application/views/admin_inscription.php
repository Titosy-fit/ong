<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- Onglets (comme dans ta page user) -->
                <div class="onglet">
                    <a href="<?= base_url('user') ?>" class="onglet_btn">Ajout des utilisateurs</a>
                    <a href="<?= base_url('fonction') ?>" class="onglet_btn">Fonction</a>
                    <a href="<?= base_url('admin_inscription') ?>" class="onglet_btn active">Ajouter administrateur</a>
                </div>

                <!-- Messages flash -->
                <?php if ($this->session->flashdata('ajout')): ?>
                    <script>Myalert.added()</script>
                <?php elseif ($this->session->flashdata('mail')): ?>
                    <script>Myalert.erreur('Email déjà utilisé')</script>
                <?php elseif ($this->session->flashdata('error')): ?>
                    <script>Myalert.erreur('Erreur lors de l\'ajout')</script>
                <?php endif; ?>

                <!-- Formulaire d'ajout admin -->
                <form action="<?= base_url('AdminInscription/register') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="mail" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Mot de passe</label>
                        <input type="password" name="pass" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Entreprise</label>
                        <input type="text" name="entreprise" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Adresse</label>
                        <input type="text" name="adresse" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Téléphone entreprise</label>
                        <input type="tel" name="tel" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Téléphone administrateur</label>
                        <input type="tel" name="teladmin" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>NIF</label>
                        <input type="text" name="nif" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>STAT</label>
                        <input type="text" name="stat" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-check"></i> Valider
                    </button>
                </form>

                <!-- Optionnel : liste des admins déjà créés -->
                <?php if (!empty($admins)): ?>
                <div class="_tableau mt-5">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Entreprise</th>
                                <th>Téléphone</th>
                                <th>Mode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= html_escape($admin->nom) ?></td>
                                <td><?= html_escape($admin->prenom) ?></td>
                                <td><?= html_escape($admin->mail) ?></td>
                                <td><?= html_escape($admin->entreprise) ?></td>
                                <td><?= html_escape($admin->teladmin) ?></td>
                                <td><?= html_escape($admin->mode) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
<!-- Debug console navigateur -->
<?php if ($this->session->flashdata('console_log')): ?>
    <script>
        console.log("=====================================");
        console.log("DEBUG ADMIN INSCRIPTION :");
        console.log("<?= addslashes($this->session->flashdata('console_log')) ?>");
        console.log("=====================================");
    </script>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
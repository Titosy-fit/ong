<div class="sidebar">
    <div class="mt-2">
        <div class="pt-2">
            <span class="sidebar-title mb-3">
                <span>Gestion des activitées </span>
            </span>

            <a href="<?= base_url('projet') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'projet') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-briefcase"></i></span>
                <span class="ms-1">Projet</span>
            </a>
            <a href="<?= base_url('budget') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'activite'  || basename($_SERVER['PHP_SELF']) == 'budget') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-tasks"></i></span>
                <span class="ms-1">Activité</span>
            </a>
            <a href="<?= base_url('dispatch') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'dispatch' || basename($_SERVER['PHP_SELF']) == 'liste' || basename($_SERVER['PHP_SELF']) == 'beneficiaire') ? 'active' : '' ?>">
                <span class="icon">
                    <i class="fas fa-paper-plane"></i>
                </span>
                <span class="ms-1">Distribution de matériel</span>
            </a>

            <?php if ($_SESSION['user_type'] == 'admin') :  ?>
                <a href="<?= base_url('depense') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'depense'  || basename($_SERVER['PHP_SELF']) == 'reliquat') ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-reply"></i></span>
                    <span class="ms-1">Gestion des dépenses</span>
                </a>
            <?php endif  ?>
            <span class="sidebar-title mt-4 mb-2">
                <span>Logistique</span>
            </span>


            <a href="<?= base_url('stock') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'stock') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-warehouse"></i></span>
                <span class="ms-1">Inventaire</span>
            </a>
            <a href="<?= base_url('materiel') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'materiel' || basename($_SERVER['PHP_SELF']) == "prix" ||  basename($_SERVER['PHP_SELF']) == "codeBarre") ? 'active' : '' ?>">
                <span class="icon"><i class="fa-solid fa-boxes-packing"></i></span>
                <span class="ms-1">Materiel</span>
            </a>

            <a href="<?= base_url('appro') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'appro') ? 'active' : '' ?>">
                <span class="icon"><i class="fa-solid fa-truck-ramp-box"></i></span>
                <span class="ms-1">Approvisionnement</span>
            </a>

            <a href="<?= base_url('trasnfert') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'trasnfert') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-exchange-alt"></i></span>
                <span class="ms-1">Transfert</span>
            </a>

            <a href="<?= base_url('pointDeVente') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'pointDeVente') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-store"></i></span>
                <span class="ms-1">Dépôt</span>
            </a>

            <span class="sidebar-title mt-4 mb-2">
                <span>Autres</span>
            </span>

            <a href="<?= base_url('fournisseur') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'fournisseur') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-truck"></i></span>
                <span class="ms-1">Fournisseur</span>
            </a>


            <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                <a href="<?= base_url('demande-mat') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'demande-mat' || basename($_SERVER['PHP_SELF']) == 'liste-demande') ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-share-alt"></i></span>
                    <span class="ms-1">Demandes de matériels </span>
                </a>
                <a href="<?= base_url('rendre-mat') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'rendre-mat') ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-undo"></i></span>
                    <span class="ms-1">Retours de matériels </span>
                </a>
                <a href="<?= base_url('listeemprunt') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'listeemprunt') ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-list"></i></span>
                    <span class="ms-1">Liste des emprunts</span>
                </a>

            <?php endif  ?>
            <?php if ($_SESSION['user_type'] == 'admin') : ?>
                <a href="<?= base_url('user') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'user') ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-user-tie"></i></span>
                    <span class="ms-1">Gestion des utilisateurs</span>
                </a>
            <?php endif  ?>
            <!-- <a href="<?= base_url('emploiMl') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'emploi' || basename($_SERVER['PHP_SELF']) == 'emploiMl') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-video"></i></span>
                <span class="ms-1">Tutoriel</span>
            </a> -->
            <span class="sidebar-title mt-4 mb-2">
            </span>
        </div>
    </div>
</div>
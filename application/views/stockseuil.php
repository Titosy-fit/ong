<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <div class="mt-4">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">

                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="mt-4">
                        <form action="<?= base_url('Stock/search_seuil') ?>" method="GET">
                            <div class="input-group">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['post'] ?? '' ?>">

                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif  ?>




                <div class="entete">
                    <h5 class="mb-3">Produit en cours de rupture de stock </h5>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <a type="button" class="btn btn-info" disabled>
                            <i class="fas fa-eye"></i>
                            Tous les stocks
                        </a>
                    <?php else : ?>
                        <a href="<?= base_url('Stock') ?>" type="button" class="btn btn-info ">
                            <i class="fas fa-eye"></i>
                            Tous les stocks
                        </a>
                    <?php endif  ?>
                </div>

                <div class="_tableau mt-4" id="stock_tableau">
                    <table class="table">
                        <thead class="table-info">
                            <tr class="to_filtre">
                                <th>
                                    <div class="container_filter">
                                        <span>Réference</span>

                                        <form action="<?= base_url('filtre_seuil') ?> " method="get">
                                            <input type="text" class="d-none" name="type" value="reference">
                                            <input type="text" class="d-none" name="ref" value="<?= $_POST['reference'] ?? 'asc' ?>">
                                            <?php
                                            if (isset($_POST['reference'])) : ?>
                                                <?php if ($_POST['reference'] == 'desc') : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down-alt"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down"></i>
                                                    </button>
                                                <?php endif  ?>
                                            <?php else : ?>
                                                <button type="submit" id="Quantite_f" class="filter">
                                                    <i class="fas fa-sort-alpha-down"></i>
                                                </button>
                                            <?php endif  ?>
                                        </form>
                                    </div>
                                </th>
                                <th>
                                    <div class="container_filter">
                                        <span>Désignation</span>

                                        <form action="<?= base_url('filtre_seuil') ?>" method="get">
                                            <input type="text" class="d-none" name="type" value="designationmateriel">
                                            <input type="text" class="d-none" name="design" value="<?= $_POST['designationmateriel'] ?? 'asc' ?>">
                                            <?php
                                            if (isset($_POST['designationmateriel'])) : ?>
                                                <?php if ($_POST['designationmateriel']  == 'desc') : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down-alt"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down"></i>
                                                    </button>
                                                <?php endif  ?>
                                            <?php else : ?>
                                                <button type="submit" id="Quantite_f" class="filter">
                                                    <i class="fas fa-sort-alpha-down"></i>
                                                </button>
                                            <?php endif  ?>
                                        </form>

                                    </div>
                                </th>
                                <th>Fiche technique </th>
                                <th>Quantité </th>
                                <th>
                                    Dépôt 
                                    <?php if (!isset($_SESSION['pv'])) : ?>
                                        <span data-id="pv_f" class="filter">
                                            <i class="fas fa-sort"></i>
                                            <div class="pv_f filtrage d-none ">
                                                <div class="filter_title">
                                                    <p class=" m-0">Filtrer : </p>
                                                </div>
                                                <form action="<?= base_url('filtre_seuil') ?>" method="get">
                                                    <div class="link_filter">
                                                        <input type="text" class="d-none" name="type" value="pv">
                                                        <a href="#" class="">
                                                            <input class="form-check-input" name="filter[0]" value="0" id="input_all" type="checkbox" checked>Tout
                                                        </a>
                                                        <?php $i = 1;
                                                        foreach ($pv as $key =>  $pointvente) : ?>
                                                            <a href="#" class="link_filtre">
                                                                <input class="form-check-input" name="filter[<?= $i ?>]" value="<?= $pointvente->idPointVente ?>" type="checkbox"><?= $pointvente->denomination_pv ?>
                                                            </a>
                                                        <?php $i++;
                                                        endforeach;  ?>
                                                    </div>
                                                    <div class="filter_btn mt-2 w-100">
                                                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">
                                                            Filtrer
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </span>
                                    <?php endif ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($datas)) : ?>
                                <?php foreach ($datas as $key => $data) : ?>
                                    <tr class="table-danger">
                                        <td><?= $data->refmateriel ?></td>
                                        <td><?= $data->designationmateriel ?></td>
                                        <td><?= $data->fiche ?></td>
                                        <td><?= $data->quantite_texte ?></td>
                                        <td><?= $data->denomination_pv ?></td>
                                    </tr>
                                <?php endforeach  ?>
                            <?php endif  ?>
                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?= $lien; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
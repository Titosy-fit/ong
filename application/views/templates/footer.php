<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
<?php if ($js == 'emploi.js') : ?>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <script src="<?php echo Myurl('public/js/bootstrap.bundle.min.js') ?>"></script>
<?php else : ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="<?php echo Myurl('public/js/bootstrap.min.js') ?>"></script>
<?php endif  ?>
    <script>
        const IDADMIN = <?php echo $_SESSION['idadmin'] ; ?>
    </script>

<!-- slider api -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- slider api -->

<script src="<?php echo Myurl('public/js/all.js') ?>"></script>
<script src="<?php echo Myurl('public/helper/Actions.js') ?>"></script>
<script src="<?php echo Myurl('public/helper/updater.js') ?>"></script>
<script src="<?php echo Myurl('public/js/Myalert.js') ?>"></script>


<script src="<?php echo Myurl('public/helper/Url.js') ?>"></script>

<?php if ($js != 'fonction.js') : ?>
    <script src="<?php echo Myurl('public/js/utility.js') ?>"></script>
<?php endif  ?>
<script src="<?php echo Myurl('public/js/pagination.js') ?>"></script>
<script src="<?php echo Myurl('public/js/alert.js') ?>"></script>
<script src="<?php echo Myurl('public/js/side.js') ?>"></script>
<script src="<?php echo Myurl('public/js/theme.js') ?>"></script>
<script src="<?php echo Myurl('public/js/') . $js; ?>"></script>

<?php if ($this->session->flashdata('projectadded')) :  ?>
    <script>
        Myalert.added();
    </script>
<?php endif ?>
<?php if ($this->session->flashdata('budgetadded')) :  ?>
    <script>
        Myalert.added();
    </script>
<?php endif ?>

</body>

</html>
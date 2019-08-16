<?php
    require_once "./controllers/viewsController.php";
    $views = new ViewsController();

    $pedidoAjax = false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?= SERVERURL ?>views/css/main.css">
    <!--====== Scripts -->
    <?php include "views/modulos/script.php"?>
</head>
<body>
    <?php
        $view = $views->exibirViewsController();

        if ($view == "login" || $view == "404"):
            if ($view == "login") {
                require_once "./views/content/login-view.php";
            } else {
                require_once "./views/content/404-view.php";
            }
        else:
            session_start(['name' => 'SBP']);
            require_once "./controllers/loginController.php";
            $lc = new loginController();

            if (!isset($_SESSION['token_sbp']) || !isset($_SESSION['usuario_sbp'])) {
                $lc->forcarFimSessaoControllador();
            }
    ?>
<!-- SideBar -->
<?php include "views/modulos/sidebar.php"; ?>

<!-- Content page-->
<section class="full-box dashboard-contentPage">
    <!-- NavBar -->
    <?php include "views/modulos/navbar.php"?>

    <!-- Content page -->
    <?php require_once $view; ?>
</section>
    <?php include "./views/modulos/logoutScript.php"; ?>
    <?php endif; ?>
<script>
    $.material.init();
</script>
</body>
</html>
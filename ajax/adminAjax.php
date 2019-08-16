<?php
    $pedidoAjax = true;
    require_once "../core/configGeral.php";

    if (isset($_POST['dni-reg'])) {
        require_once "../controllers/adminController.php";
        $insAdmin = new adminController();

        if (isset($_POST['dni-reg']) && (isset($_POST['nombre-reg']))) {
            echo $insAdmin->adicionarAdminController();
        }
    } else {
        session_start();
        session_destroy();
        echo '<script> window.location.href="'. SERVERURL .'login/" </script>';
    }
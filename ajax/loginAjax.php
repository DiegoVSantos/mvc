<?php

$pedidoAjax = true;
require_once "../core/configGeral.php";

if (isset($_GET['Token'])) {
    require_once "../controllers/loginController.php";
    $logout = new loginController();
    echo $logout->encerrarSessaoController();
} else {
    session_start();
    session_destroy();
    echo '<script> window.location.href="' . SERVERURL . 'login/" </script>';
}
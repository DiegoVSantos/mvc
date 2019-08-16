<?php
require_once "./core/configGeral.php";
require_once "./controllers/viewsController.php";

$template = new ViewsController();
$template->exibirTemplateController();
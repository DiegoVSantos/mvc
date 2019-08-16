<?php

require_once "./models/viewsModel.php";
class ViewsController extends ViewsModel {

    public function exibirTemplateController() {
        return require_once "./views/template.php";
    }

    public function exibirViewsController() {
        if (isset($_GET['views'])) {
            $route = explode("/", $_GET['views']);
            $resposta = ViewsModel::exibirViewsModel($route[0]);
        } else {
            $resposta = "login";
        }
        return $resposta;
    }
}
<?php
class ViewsModel {
    protected function exibirViewsModel($views) {
        $whiteList = [
            "admin",
            "adminlist",
            "adminsearch",
            "book",
            "bookconfig",
            "bookinfo",
            "catalog",
            "category",
            "categorylist",
            "client",
            "clientlist",
            "clientsearch",
            "company",
            "companylist",
            "home",
            "myaccount",
            "mydata",
            "provider",
            "providerlist",
            "search",
        ];

        if (in_array($views, $whiteList)) {
            if (is_file("./views/content/".$views."-view.php")) {
                $conteudo = "./views/content/".$views."-view.php";
            } else {
                $conteudo = "login";
            }
        } elseif ($views == "login") {
            $conteudo = "login";
        } elseif ($views == "index") {
            $conteudo = "login";
        } else {
            $conteudo = "404";
        }
        return $conteudo;
    }
}
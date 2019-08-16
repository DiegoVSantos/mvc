<?php
if ($pedidoAjax) {
    require_once "../core/mainModel.php";
} else {
    require_once "./core/mainModel.php";
}

class loginModel extends mainModel {

    protected function iniciarSessaoModel($dados) {
        $sql = parent::conexao()->prepare("SELECT * FROM cuenta WHERE CuentaUsuario = :Usuario AND CuentaClave = :Clave AND CuentaEstado = 'Ativo'");
        $sql->bindParam(":Usuario", $dados['Usuario']);
        $sql->bindParam(":Clave", $dados['Clave']);
        $sql->execute();
        return $sql;
    }

    protected function encerrarSessaoModel($dados) {
        if ($dados['Usuario'] != "" && $dados['Token_S'] == $dados['Token']) {
            $atualizarBitacora = mainModel::atualizarBitacora($dados['Codigo'], $dados['Hora']);

            if ($atualizarBitacora->rowCount() == 1) {
                session_unset();
                session_destroy();

                $resposta = "true";
            } else {
                $resposta = "false";
            }
        } else {
            $resposta = "false";
        }
        return $resposta;
    }

}
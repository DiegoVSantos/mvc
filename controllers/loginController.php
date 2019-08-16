<?php
if ($pedidoAjax) {
    require_once "../models/loginModel.php";
} else {
    require_once "./models/loginModel.php";
}

class loginController extends loginModel {
    public function iniciarSessaoController() {
        $usuario = mainModel::limparString($_POST['usuario']);
        $clave = mainModel::limparString($_POST['clave']);
        $clave = mainModel::encryption($clave);
        $dadosLogin = [
            'Usuario' => $usuario,
            'Clave' => $clave
        ];

        $dadosConta = loginModel::iniciarSessaoModel($dadosLogin);

        if ($dadosConta->rowCount() == 1) {
            $row = $dadosConta->fetch();

            $fechaAtual = date('Y-m-d');
            $anoAtual = date('Y');
            $horaAtual = date('H:i:s');

            $consulta1 = mainModel::consultaSimples("SELECT id FROM bitacora");
            $numero = ($consulta1->rowCount()) + 1;

            $codigoB = mainModel::gerarCodigoAleatorio("CB", 7, $numero);

            $dadosBitacora = [
                'Codigo'        => $codigoB,
                'Fecha'         => $fechaAtual,
                'HoraInicio'    => $horaAtual,
                'HoraFinal'     => "Sem Registro",
                'Tipo'          => $row['CuentaTipo'],
                'Year'          => $anoAtual,
                'Cuenta'        => $row['CuentaCodigo'],
            ];

            $adicionarBitacora = mainModel::adicionarBitacora($dadosBitacora);

            if ($adicionarBitacora->rowCount() >= 1) {
                session_start(['name' => 'SBP']);
                $_SESSION['usuario_sbp'] = $row['CuentaUsuario'];
                $_SESSION['tipo_sbp'] = $row['CuentaTipo'];
                $_SESSION['privilegio_sbp'] = $row['CuentaPrivilegio'];
                $_SESSION['foto_sbp'] = $row['CuentaFoto'];
                $_SESSION['token_sbp'] = md5(uniqid(mt_rand(), true));
                $_SESSION['codigoConta_sbp'] = $row['CuentaCodigo'];
                $_SESSION['codigoBitacora_sbp'] = $codigoB;

                if ($row['CuentaTipo'] == "Administrador") {
                    $url = SERVERURL."home/";
                } else {
                    $url = SERVERURL."catalog/";
                }

                return $urlLocation = '<script> window.location="'.$url.'" </script>';
            } else {
                $alertas = [
                    'alerta' => 'simples',
                    'titulo' => "Erro Inesperado!",
                    'texto' => "Impossivel iniciar sessão no momento. Por favor, tente novamente mais tarde.",
                    'tipo' => "error"
                ];
            }
        } else {
            $alertas = [
                'alerta' => 'simples',
                'titulo' => "Erro!",
                'texto' => "Usuario / Senha incorretos ou Conta desabilitada",
                'tipo' => "error"
            ];
            return mainModel::sweetAlert($alertas);
        }
    }

    public function encerrarSessaoController() {
        session_start(['name' => 'SBP']);

        $token = mainModel::decryption($_GET['Token']);
        $hora = date('H:i:s');
        $dados = [
            'Usuario' => $_SESSION['usuario_sbp'],
            'Token_S' => $_SESSION['token_sbp'],
            'Token' => $token,
            'Codigo' => $_SESSION['codigoBitacora_sbp'],
            'Hora' => $hora
        ];

        return loginModel::encerrarSessaoModel($dados);
    }

    public function forcarFimSessaoControllador() {
        session_destroy();
        return header("Location: ".SERVERURL."login/");
    }
}
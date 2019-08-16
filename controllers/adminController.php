<?php
    if ($pedidoAjax) {
        require_once "../models/adminModel.php";
    } else {
        require_once "./models/adminModel.php";
    }

    class adminController extends adminModel {
        /**
         * <p>Função responsável por adicionar um Admin</p>
         * @return string
         */
        public function adicionarAdminController() {
            $dni = mainModel::limparString($_POST['dni-reg']);
            $nome = mainModel::limparString($_POST['nombre-reg']);
            $apellido = mainModel::limparString($_POST['apellido-reg']);
            $telefono = mainModel::limparString($_POST['telefono-reg']);
            $direccion = mainModel::limparString($_POST['direccion-reg']);

            $usuario = mainModel::limparString($_POST['usuario-reg']);
            $password1 = mainModel::limparString($_POST['password1-reg']);
            $password2 = mainModel::limparString($_POST['password2-reg']);
            $email = mainModel::limparString($_POST['email-reg']);
            $genero = mainModel::limparString($_POST['optionsGenero']);
            $privilegio = mainModel::limparString($_POST['optionsPrivilegio']);

            if ($genero == "Masculino") {
                $foto = "Male3Avatar.png";
            } else {
                $foto = "Female3Avatar.png";
            }

            if ($password1 != $password2) {
                $alertas = [
                    'alerta' => 'simples',
                    'titulo' => "Erro!",
                    'texto' => "As senhas inseridas não conferem. Tente novamente",
                    'tipo' => "error"
                ];
            } else {
                $consulta1 = mainModel::consultaSimples("SELECT AdminDNI FROM admin WHERE AdminDNI = '$dni'");
                if ($consulta1->rowCount() >= 1) {
                    $alertas = [
                        'alerta' => 'simples',
                        'titulo' => "Erro!",
                        'texto' => "Registro DNI inserido já cadastrado. Tente novamente.",
                        'tipo' => "error"
                    ];
                } else {
                    if ($email != "") {
                        $consulta2 = mainModel::consultaSimples("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail = '$email'");
                        $ec = $consulta2->rowCount();
                    } else {
                        $ec = 0;
                    }

                    if ($ec >= 1) {
                        $alertas = [
                            'alerta' => 'simples',
                            'titulo' => "Erro!",
                            'texto' => "Email inserido já cadastrado. Tente novamente.",
                            'tipo' => "error"
                        ];
                    } else {
                        $consulta3 = mainModel::consultaSimples("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario = '$usuario'");
                        if ($consulta3->rowCount() >=1 ) {
                            $alertas = [
                                'alerta' => 'simples',
                                'titulo' => "Erro!",
                                'texto' => "Usuario inserido já cadastrado. Tente novamente.",
                                'tipo' => "error"
                            ];
                        } else {
                            $consulta4 = mainModel::consultaSimples("SELECT id FROM cuenta");
                            $numero = ($consulta4->rowCount() + 1);

                            $codigo = mainModel::gerarCodigoAleatorio('AC', 7, $numero);

                            $chave = mainModel::encryption($password1);

                            $dadosConta = [
                                'Codigo'        => $codigo,
                                'Privilegio'    => $privilegio,
                                'Usuario'       => $usuario,
                                'Clave'         => $chave,
                                'Email'         => $email,
                                'Estado'        => "Ativo",
                                'Tipo'          => "Administrador",
                                'Genero'        => $genero,
                                'Foto'          => $foto,
                            ];

                            $adicionarConta = mainModel::adicionarConta($dadosConta);

                            if ($adicionarConta->rowCount() >= 1) {
                                $dadosAdmin = [
                                    'DNI' => $dni,
                                    'Nombre' => $nome,
                                    'Apellido' => $apellido,
                                    'Telefono' => $telefono,
                                    'Direccion' => $direccion,
                                    'Codigo' => $codigo,
                                ];

                                $adicionarAdmin = adminModel::adicionarAdminModel($dadosAdmin);

                                if ($adicionarAdmin->rowCount() >= 1) {
                                    $alertas = [
                                        'alerta' => 'limpar',
                                        'titulo' => "Administrador Cadastrado!",
                                        'texto' => "Administrador cadastrado com sucesso.",
                                        'tipo' => "success"
                                    ];
                                } else {
                                    mainModel::removerConta($codigo);
                                    $alertas = [
                                        'alerta' => 'simples',
                                        'titulo' => "Erro Inesperado!",
                                        'texto' => "Erro ao registrar o usuário. Tente novamente.",
                                        'tipo' => "error"
                                    ];
                                }
                            } else {
                                $alertas = [
                                    'alerta' => 'simples',
                                    'titulo' => "Erro Inesperado!",
                                    'texto' => "Erro ao registrar o usuário. Tente novamente.",
                                    'tipo' => "error"
                                ];
                            }
                        }
                    }
                }
            }

            return mainModel::sweetAlert($alertas);
        }

        public function paginacaoAdminController($pagina, $qtdRegistros, $privilegio, $codigoUsuario) {
            $pagina = mainModel::limparString($pagina);
            $qtdRegistros = mainModel::limparString($qtdRegistros);
            $privilegio = mainModel::limparString($privilegio);
            $codigoUsuario = mainModel::limparString($codigoUsuario);
            $tabela = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $qtdRegistros) - $qtdRegistros) : 0;

            $conexao = mainModel::conexao();

            $dados = $conexao->query("
                SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE CuentaCodigo != '$codigoUsuario' AND id != '1' ORDER BY AdminNombre ASC LIMIT $inicio,$qtdRegistros
            ");
            $dados = $dados->fetchAll();

            $total = $conexao->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();

            $totalPaginas = ceil($total/$qtdRegistros);

            $tabela .= '<div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">DNI</th>
                                    <th class="text-center">NOMBRES</th>
                                    <th class="text-center">APELLIDOS</th>
                                    <th class="text-center">TELÉFONO</th>
                                    <th class="text-center">A. CUENTA</th>
                                    <th class="text-center">A. DATOS</th>
                                    <th class="text-center">ELIMINAR</th>
                                </tr>
                                </thead>
                                <tbody>';

            if ($total >= 1 && $pagina <= $totalPaginas) {
                $contador = $inicio + 1;
                foreach ($dados as $dado) {
                    $tabela .= '<tr>
                                    <td>'.$contador.'</td>
                                    <td>'.$dado['AdminDNI'].'</td>
                                    <td>'.$dado['AdminNombre'].'</td>
                                    <td>'.$dado['AdminApellido'].'</td>
                                    <td>'.$dado['AdminTelefono'].'</td>
                                    <td>
                                        <a href="#!" class="btn btn-success btn-raised btn-xs">
                                            <i class="zmdi zmdi-refresh"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#!" class="btn btn-success btn-raised btn-xs">
                                            <i class="zmdi zmdi-refresh"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form>
                                            <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>';
                }
            } else {
                $tabela .= '
                    <tr>
                        <td colspan="5">Nenhum Registro Encontrado</td>
                    </tr>
                ';
            }

            $tabela .= "</tbody>
                </table>
            </div>";
            return $tabela;
        }
    }
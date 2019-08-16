<?php
    if ($pedidoAjax) {
        require_once "../core/configAPP.php";
    } else {
        require_once "./core/configAPP.php";
    }
    class mainModel {
        protected function conexao() {
            $link = new PDO(SGBD, USER, PASS);
            return $link;
        }

        protected function consultaSimples($consulta) {
            $resposta = self::conexao()->prepare($consulta);
            $resposta->execute();
            return $resposta;
        }

        protected function adicionarConta($dados) {
            $sql = self::conexao()->prepare("INSERT INTO cuenta(CuentaCodigo, CuentaPrivilegio, CuentaUsuario, CuentaClave, CuentaEmail, CuentaEstado, CuentaTipo, CuentaGenero, CuentaFoto) VALUES(:Codigo, :Privilegio, :Usuario, :Clave, :Email, :Estado, :Tipo, :Genero, :Foto)");
            $sql->bindParam(":Codigo", $dados['Codigo']);
            $sql->bindParam(":Privilegio", $dados['Privilegio']);
            $sql->bindParam(":Usuario", $dados['Usuario']);
            $sql->bindParam(":Clave", $dados['Clave']);
            $sql->bindParam(":Email", $dados['Email']);
            $sql->bindParam(":Estado", $dados['Estado']);
            $sql->bindParam(":Tipo", $dados['Tipo']);
            $sql->bindParam(":Genero", $dados['Genero']);
            $sql->bindParam(":Foto", $dados['Foto']);
            $sql->execute();
            return $sql;
        }

        protected function removerConta($codigo) {
            $sql = self::conexao()->prepare("DELETE FROM cuenta WHERE CuentaCodigo = :Codigo");
            $sql->bindParam(":Codigo", $codigo);
            $sql->execute();
            return $sql;
        }

        protected function adicionarBitacora($dados) {
            $sql = self::conexao()->prepare("INSERT INTO bitacora (BitacoraCodigo, BitacoraFecha, BitacoraHoraInicio, BitacoraHoraFinal, BitacoraTipo, BitacoraYear, CuentaCodigo) VALUES (:Codigo, :Fecha, :HoraInicio, :HoraFinal, :Tipo, :Year, :Cuenta)");
            $sql->bindParam(":Codigo", $dados['Codigo']);
            $sql->bindParam(":Fecha", $dados['Fecha']);
            $sql->bindParam(":HoraInicio", $dados['HoraInicio']);
            $sql->bindParam(":HoraFinal", $dados['HoraFinal']);
            $sql->bindParam(":Tipo", $dados['Tipo']);
            $sql->bindParam(":Year", $dados['Year']);
            $sql->bindParam(":Cuenta", $dados['Cuenta']);
            $sql->execute();
            return $sql;
        }

        protected function atualizarBitacora($codigo, $horaFinal) {
            $sql = self::conexao()->prepare("UPDATE bitacora SET BitacoraHoraFinal = :HoraFinal WHERE BitacoraCodigo = :Codigo");
            $sql->bindParam(":HoraFinal", $horaFinal);
            $sql->bindParam(":Codigo", $codigo);
            $sql->execute();
            return $sql;
        }

        protected function removerBitacora($codigo) {
            $sql = self::conexao()->prepare("DELETE FROM bitacora WHERE CuentaCodigo = :Codigo");
            $sql->bindParam(":Codigo", $codigo);
            $sql->execute();
            return $sql;
        }

        public function encryption($string) {
            $output = false;
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output = base64_encode($output);
            return $output;
        }

        protected function decryption($string) {
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;
        }

        protected function gerarCodigoAleatorio($letra,$qtdCaraters,$num) {
            for ($i = 1; $i <= $qtdCaraters; $i++) {
                $numero = rand(0,9);
                $letra .= $numero;
            }
            return $letra."-".$num;
        }

        protected function limparString($string) {
            $string = trim($string);
            $string = stripslashes($string);
            $string = str_ireplace("<script>", "", $string);
            $string = str_ireplace("</script>", "", $string);
            $string = str_ireplace("<script src", "", $string);
            $string = str_ireplace("<script type=", "", $string);
            $string = str_ireplace("SELECT * FROM", "", $string);
            $string = str_ireplace("DELETE FROM", "", $string);
            $string = str_ireplace("INSERT INTO", "", $string);
            $string = str_ireplace("--", "", $string);
            $string = str_ireplace("^", "", $string);
            $string = str_ireplace("[", "", $string);
            $string = str_ireplace("]", "", $string);
            $string = str_ireplace("==", "", $string);

            return $string;
        }

        protected function sweetAlert($dados) {
            if ($dados['alerta'] == "simples") {
                $alerta = "
                    <script>
                        swal(
                            '{$dados['titulo']}',
                            '{$dados['texto']}',
                            '{$dados['tipo']}'
                        );
                    </script>
                ";
            } elseif ($dados['alerta'] == "confirmar") {
                $alerta = "
                    <script>
                        swal({
                          title: '{$dados['titulo']}',
                          text: '{$dados['texto']}',
                          type: '{$dados['tipo']}',
                          confirmButtonText: 'Confirmar'
                        }).then(function() {
                          location.reload();
                        });
                    </script>
                ";
            } elseif ($dados['alerta'] == "limpar") {
                $alerta = "
                    <script>
                        swal({
                          title: '{$dados['titulo']}',
                          text: '{$dados['texto']}',
                          type: '{$dados['tipo']}',
                          confirmButtonText: 'Confirmar'
                        }).then(function() {
                          $('.FormularioAjax')[0].reset;
                        });
                    </script>
                ";
            }

            return $alerta;
        }
    }
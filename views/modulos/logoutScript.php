<script>
    $(document).ready(function () {
        $('.btn-exit-system').on('click', function(e){
            e.preventDefault();
            var Token = $(this).attr('href');
            swal({
                title: 'Tem Certeza?',
                text: "A sessão atual será encerrada",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#03A9F4',
                cancelButtonColor: '#F44336',
                confirmButtonText: '<i class="zmdi zmdi-run"></i> Sim, Sair!',
                cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Não, Cancelar!'
            }).then(function () {
                $.ajax({
                    url: '<?= SERVERURL ?>ajax/loginAjax.php?Token='+Token,
                    success: function (data) {
                        if (data == "true") {
                            swal({
                                title: 'Sessão Encerrada!',
                                text: "Até Mais! =)",
                                type: 'success',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showCancelButton: false,
                            }).then(function () {
                                window.location.href = '<?= SERVERURL ?>login/';
                            });
                        } else {
                            swal(
                                "Erro!",
                                "Falha ao encerrar a sessão",
                                "error"
                            );
                        }
                    }
                });
            });
        })
    })
</script>
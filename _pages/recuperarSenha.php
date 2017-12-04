<?php


$phpGet = filter_input_array(INPUT_GET);

if (empty($phpGet['ID']) || empty($phpGet['ticket']))
{
    include_once ("/_pages/404.php");
}
else
{
    $recuperarSenha = getRest(str_replace(["{IDLogin}","{IDToken}"], ['-1', $phpGet['ticket']], $endPoint['tokensenha']));
    
    if ($recuperarSenha)
    {
?>    
    <script type="text/javascript">
        function recuperarSenha()
        {
            $('#resultRecSenha').html('Alterando senha...');

            var recSenha = $('#recSenha').val();
            var recConfSenha = $('#recConfSenha').val();

            $.post('/_pages/atualizarCadastro.php', {postnovasenha:recSenha,
                                                     postconfnovasenha:recConfSenha,
                                                     posttoken:'<?= $phpGet['ticket'] ?>',
                                                     postcadastrarsenha:'<?= md5("cadastrarSenha") ?>'},
            function(dataRecSenha)
            {
                $('#resultRecSenha').html(dataRecSenha);
            });            
        
            document.getElementById("recSenha").value = "";
            document.getElementById("recConfSenha").value = "";
            
            return false;
        }
    </script>

    <section class="conta">

        <h4>Recuperação de senha</h4>
        <div class="row">
            <div class="col-md-3">
                <ul class="nav tabs-left">	  					
                    <li class="active"><a href="#inf-trocasenha" data-toggle="tab">Nova senha</a></li>
                </ul>
            </div>
            <div class="tab-content col-md-9">

                <!-- pane login -->
                <div class="tab-pane active" id="inf-trocasenha">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="recSenhaForm" id="recSenhaForm" method="post" action="/" onSubmit="return recuperarSenha()">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nova senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="recSenha" id="recSenha" class="form-control" placeholder="Nova senha" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Repita a senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="recConfSenha" id="recConfSenha" class="form-control" placeholder="Repita a senha" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3"></label>
                                <div class="col-sm-9 col-md-6" id="resultRecSenha"></div>
                            </div>                            
                            <button type="reset" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Alterar</button>
                        </form>
						
							
                    </div>
                </div>
                <!-- fim pane -->                    
            </div>
        </div>
    </section>	    
<?php    
    }
    else 
    {
    ?>
        <section class="conta">
            <h4>Token de troca de senha expirado.</h4>
            <h3>Se você ainda não alterou sua senha, tente novamente.</h3>
            <h3>Obrigado.</h3>
        </section>
<?php        
    }
}
?>

<?php

?>

<section class="content-checkpoint">
	<div class="container">
		<div class="row">
			<div class="linha">
				<div class="box-step col-xs-4 active">
					<div class="circulo"></div>
					<span>Identificação</span>
				</div>
				<div class="box-step col-xs-4">
					<div class="circulo"></div>
					<span>Endereço e Pagamento</span>
				</div>
				<div class="box-step col-xs-4">
					<div class="circulo"></div>
					<span>Confirmação</span>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	function obterBearerCK() {
		$('#resultBearerCK').html('Autenticando...');

		var loginEmailMC = $('#loginEmailCK').val();
		var loginSenhaMC = $('#loginSenhaCK').val();

		$.post('/_pages/login.php', {postlogin:loginEmailMC,postsenha:loginSenhaMC},
	function(data) {
			if (data.substring(0,2) == "!!") {
				$('#resultBearerCK').html(data.substring(2));

				document.getElementById("loginEmailCK").value = '';
				document.getElementById("loginSenhaCK").value = '';
			} else {
				$('#resultBearerCK').html(data);

				document.autForm.submit();
			}
		});
		return false;
	}
</script>

<section class="ordem">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel-group ordem-panel-login" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#panel-login">Já sou cliente</a>
							</h4>
						</div>
						<div id="panel-login" class="panel-collapse collapse in">
							<div class="panel-body">
								<form name="loginFormCK" id="loginFormCK" method="post" action="/checkout" onSubmit="return obterBearerCK()">
									<div class="form-group"><input type="text" class="form-control" name="loginEmailCK" id="loginEmailCK" placeholder="E-mail" required="required" /></div>
									<div class="form-group"><input type="password" class="form-control" name="loginSenhaCK" id="loginSenhaCK" placeholder="Senha" required="required" /></div>
									<div class="text-center" id="resultBearerCK"></div>
									<div class="text-center form-group"><button type="submit" class="btn btn-lg btn-primary">ENTRAR E COMPRAR</button></div>
								</form>
							</div>
						</div>
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#panel-cadastre-se">
									Não sou cliente ou esqueci a minha senha
								</a>
							</h4>
						</div>
						<div id="panel-cadastre-se" class="panel-collapse collapse">
							<form method="post" action="/">
								<div class="panel-body">
									<div class="form-group">
											Por favor efetue o seu cadastro para concluir a sua compra.<br><br>
											Se você esqueceu a sua senha, clique no botão "IR PARA CADASTRO", e em seguida clique em "ESQUECI MINHA SENHA".
									</div>
									<div class="form-group"></div>
									<div class="text-center form-group"><button type="submit" class="btn btn-lg btn-primary">IR PARA CADASTRO</button></div>
								</div>
								<input type="hidden" name="cadastroResult" id="cadastroResult" value="<?= md5("cadastro") ?>">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
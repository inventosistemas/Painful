<?php
	if (!empty($dadosLogin['CarrinhoId'])) {
		$IDCarrinho = $dadosLogin['CarrinhoId'];
	} elseif (!empty($_SESSION['carrinho'])) {
		$IDCarrinho = $_SESSION['carrinho'];
	} else {
		$IDCarrinho = -1;
	}    
	$esperaResultado = '<div class="panel-heading">Atualizando seu carrinho...</div>'
									 . '<div class="row">'
									 . '<div class="cart-lf"></div>'
									 . '<div class="cart-ct">'
									 . '<br><i class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></i>'
									 . '</div>'
									 . '<div class="cart-rt"></div>'
									 . '<div class="make-space-bet"></div>'
									 . '</div>';

	if (!empty($numCarrinho)) {
		$carrinho = getRest(str_replace("{IDCarrinho}", $numCarrinho, $endPoint['obtercarrinho']));
	}
?>
<section class="content-cart">
	<div class="container">
		<h4 class="title-page">Carrinho</h4>
		<div id="cartData" class="cart-box">
			<?php if (empty($IDCarrinho) || $IDCarrinho <= 0) { ?>
				<div class="panel-heading">Seu carrinho está vazio</div>
				<div class="row">
					<div class="cart-lf"></div>
					<div class="cart-ct">
						<br>Não há produtos no seu carrinho.
					</div>
					<div class="cart-rt"></div>
					<div class="make-space-bet"></div>
				</div>            
			<?php } ?>
		</div>
	</div>
</section>

<?php if (!empty($IDCarrinho) && $IDCarrinho > 0) { ?>
	<script type="text/javascript">
		$('#cartData').html('<?= $esperaResultado ?>');
		$.post('/_pages/carrinhoEditar.php', {
			postidcarrinho:'<?= $IDCarrinho ?>',
			postcarrinho:'<?= md5("editCarrinho") ?>',
			posttipoedicao:'<?= md5("atualizar") ?>',
			posttipocarrinho:'<?= md5("pagina") ?>'
		},        
		function(dataCarrinho) {
			$('#cartData').html(dataCarrinho);
		});                                            
	</script>    

	<div class="footer-cart">
		<div class="container">
			<div class="inner-ft">
				<div class="cart-cep">
					<form name="consultarCEP" id="consultarCEP" method="post" action="/carrinho" onsubmit="return atualizarFrete();" >
						<div class="box-title">
							<p class="title">Consulte o frete</p>
							<p class="sub-title">Por favor informe o CEP</p>
						</div>
						<div class="box-form">
							<input class="textbox" type="text" name="CEPCarrinho" id="CEPCarrinho" placeholder="00000" maxlength="5" required="required" /> - <input class="textbox" type="text" name="CEPCompCarrinho" id="CEPCompCarrinho" placeholder="000" maxlength="3" required="required" /><!--
							--><button type="submit" class="btn btn-submit">Consultar</button>
							<i id="atualizandoCEP"></i>
							<a href="http://www.buscacep.correios.com.br/sistemas/buscacep/BuscaCepEndereco.cfm" target="_blank" class="get-cep">Não sei o meu CEP</a>
						</div>
					</form>
				</div>
				<div class="box-coupon">
					<form name="enviarCupomDesconto" id="enviarCupom">
						<div class="box-title">
							<p class="title">Cupom de Desconto</p>
							<p class="sub-title">Informe o código</p>
						</div>
						<div class="box-form">
							<?php if($carrinho['CodigoVoucher']) : ?>
								<input class="textbox" value="<?= $carrinho['CodigoVoucher']; ?>" type="text" name="Cupom" id="Cupom" maxlength="30" disabled /><!--
								--><button id="addCupom" type="submit" class="btn rmv-cupom">Remover</button>
							<?php else : ?>
								<input class="textbox" value="" type="text" name="Cupom" id="Cupom" maxlength="30" /><!--
								--><button id="addCupom" type="submit" class="btn">Enviar</button>
							<?php endif; ?>
						</div>
					</form>
				</div>
			</div>
			<div class="cart-bt">
				<form method="post" action="/checkout" class="box-agree">
					<label><input type="checkbox" name="mailling" value="1"><span>Quero receber ofertas e desconto por e-mail</span></label>
					<div class="box-btn">
						<button type="submit" class="btn btn-lg btn-primary">Finalizar a compra</button>
					</div>
				</form>
				<script type="text/javascript">
					var meus_campos = {
						'mailling': 'mailling'
					 };
					options = { fieldMapping: meus_campos };
					RdIntegration.integrate('19be3ce6a7bd0b40fbe376160c87784f', 'ofertas', options);  
				</script>
			</div>
		</div>
	</div>
<?php } ?>
<div class="make-space-bet clearfix"></div>

<div class="modal fade" id="modal-cupom" tabindex="-1" role="dialog" aria-labelledby="modal-cupom-box">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content modal-carrinho-content">
			<div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</div>
			<div class="modal-body">
				<div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>
				<div class="title-modal">
					<h2>Cupom de desconto</h2>
				</div>
				<div class="info-cupom"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function retirarCarrinho(IDProduto) {
		$('#cartData').html('<?= $esperaResultado ?>');
		$.post('/_pages/carrinhoEditar.php', {
			postidproduto:IDProduto,
			postidcarrinho:'<?= $IDCarrinho ?>',
			postcarrinho:'<?= md5("editCarrinho") ?>',
			posttipoedicao:'<?= md5("remover") ?>',
			posttipocarrinho:'<?= md5("pagina") ?>'
		},
		function(dataCarrinho) {
			$('#cartData').html(dataCarrinho);
		});                
	}
	function atualizarQtde(IDProduto) {
		var qtdeAlterar = document.getElementById("qtdeItemCarriho" + IDProduto).value;
		$.post('/_pages/carrinhoEditar.php', {
			postidproduto:IDProduto,
			postqtdeproduto:qtdeAlterar,
			postidcarrinho:'<?= $IDCarrinho ?>',
			postcarrinho:'<?= md5("editCarrinho") ?>',
			posttipoedicao:'<?= md5("alterarqdte") ?>',
			posttipocarrinho:'<?= md5("pagina") ?>'
		},
		function(dataCarrinho) {
			$('#cartData').html(dataCarrinho);
			atualizarFrete();
		});                
	}    
    
	function atualizarFrete() {
		var CEPCarrinho = $('#CEPCarrinho').val();
		var CEPCompCarrinho = $('#CEPCompCarrinho').val();
		$('#atualizandoCEP').html('Atualizando...');
		$.post('/_pages/carrinhoEditar.php', {
			postidcarrinho:'<?= $IDCarrinho ?>',
			postcepcarrinho: CEPCarrinho + '-' + CEPCompCarrinho,
			posttipoedicao:'<?= md5("calcularCEP") ?>',
			postcarrinho:'<?= md5("editCarrinho") ?>',
			posttipocarrinho:'<?= md5("pagina") ?>'
		},
		function(dataCarrinho) {
			$('#cartData').html(dataCarrinho);
		});
		$('#atualizandoCEP').html('');
		return false;
	}

	//Cupom
	var IDParceiro = <?php if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) { echo $dadosLogin['ID']; } else{ echo 'null'; } ?>;

	//addCupom
	$('#addCupom').on('click', function (e) {
		e.preventDefault();
		var _this = $(this),
			getForm = $(this).closest('form'),
			getCupom = getForm.find('#Cupom').val();
		if(!$(this).hasClass('rmv-cupom')){
			if (getCupom == '') {
				$('#modal-cupom .info-cupom').empty();
				$('#modal-cupom .info-cupom').append('<p>Insira um cupom.</p>');
				$('#modal-cupom').modal('show');
			} else {
				$.ajax({
					url: '<?= URLWebAPI ?>v1/carrinho/utilizarcupom',
					type: 'POST',
					data: {
						'CarrinhoID': <?= $IDCarrinho ?>,
						'NumeroCupom': getCupom,
						'ParceiroID': IDParceiro
					}
				}).done(function (data) {
					if (data.Erro) {
						$('#modal-cupom .info-cupom').empty();
						if (data.Mensagem.search(/autenticação/) != -1) {
							$('#modal-cupom .info-cupom').append('<p>Para validar o cupom é necessário estar logado.<a id="op-lg-md" href="#modal-login" data-toggle="modal">Fazer login</a></p>');
							$('#op-lg-md').bind('click', function () {
								$('#modal-cupom').modal('hide');
							});
						} else {
							$('#modal-cupom .info-cupom').append('<p>' + data.Mensagem + '</p>');
						}
						$('#modal-cupom').modal('show');
					} else {
						$.post('/_pages/carrinhoEditar.php', {
							postidcarrinho: '<?= $IDCarrinho ?>',
							postcarrinho: '<?= md5("editCarrinho") ?>',
							posttipocarrinho: '<?= md5("pagina") ?>'
						},
						function (dataCarrinho) {
							$('#cartData').html(dataCarrinho);
						});
						getForm.find('#Cupom').attr('disabled', 'disabled');
						_this.addClass('rmv-cupom');
						_this.text('Remover');
					}
				}).fail(function (data) {
					if (data.Erro) {
						$('#modal-cupom .info-cupom').empty();
						$('#modal-cupom .info-cupom').append('<p>' + data.Mensagem + '</p>');
						$('#modal-cupom').modal('show');
					}
				});
			}
	 	} else {
			$.ajax({
				url: '<?= URLWebAPI ?>v1/carrinho/limparcupom',
				type: 'POST',
				data: {
					'CarrinhoID': <?= $IDCarrinho ?>
				}
			}).done(function (data) {
				$.post('/_pages/carrinhoEditar.php', {
					postidcarrinho: '<?= $IDCarrinho ?>',
					postcarrinho: '<?= md5("editCarrinho") ?>',
					posttipocarrinho: '<?= md5("pagina") ?>'
				},
				function (dataCarrinho) {
					$('#cartData').html(dataCarrinho);
				});
				getForm.find('#Cupom').removeAttr('disabled');
				_this.removeClass('rmv-cupom');
				_this.text('Enviar');
			});
		}
	});
</script>
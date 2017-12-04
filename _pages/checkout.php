<?php
              
	if (empty($dadosLogin['CarrinhoId'])) {
		include_once ("/_pages/404.php");
		die;
	} else {
		$carrinho = getRest(str_replace('{IDCarrinho}', $dadosLogin['CarrinhoId'], $endPoint['obtercarrinho']));

		if (empty($carrinho['Itens'])){
			include_once ("/_pages/404.php");
			die;        
		}
	}
var_dump($carrinho);
	$esperaResultado = '<div align="center"><span class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></span></div>';

	$parcelamento = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$dadosLogin['CarrinhoId'], $carrinho['Total']], $endPoint['parcarrinho']));

	// Procura pela parcela Zero, referente boleto
	$parBoleto = array_search("0", array_column($parcelamento, 'Numero')); // busca pela parcela 0 (boleto)

	// Bandeiras de cartão
	$bandeirasCartao = getRest(str_replace("{valorCarrinho}", $dadosLogin['CarrinhoId'], $endPoint['formaspagamento']));

	// Endereços de entrega
	$enderecos = getRest(str_replace("{IDParceiro}", $dadosLogin['Parceiro']['ID'], $endPoint['endcadastral']));
	$enderecoCarrinho = (!empty($enderecos['Enderecos'][1]['ID'])) ? $enderecos['Enderecos'][1] : $enderecos['Enderecos'][0];
?>

<section class="content-checkpoint">
	<div class="container">
		<div class="row">
			<div class="linha">
					<div class="box-step col-xs-4" id="checkpointid"></div>
					<div class="box-step col-xs-4" id="checkpointendpgto"></div>
					<div class="box-step col-xs-4" id="checkpointconf"></div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$('#checkpointid').html('<div class="circulo"></div><span>Identificação</span>');
	$('#checkpointendpgto').html('<div class="circulo"></div><span>Endereço e Pagamento</span>');
	$('#checkpointconf').html('<div class="circulo"></div><span>Confirmação</span>');
</script>

<script type="text/javascript">
	function GerarHash() {
		var hash;

		var cc = new Moip.CreditCard({
			number: $("#pgNumCartao").val(),
			cvc: $("#pgCVC").val(),
			expMonth: $("#pgMedVenc").val(),
			expYear: $("#pgAnoVenc").val(),
			pubKey: '<?= MOIPPublicKey ?>'
		});
		if (cc.isValid()) {
			hash = cc.hash();
		} else {
			hash = '!!Cartão de crédito inválido.';
		}

		return hash;
	}
</script>

<script type="text/javascript">
	function valoresCarrinho() {
		var tipoPagto = $('input[name=pgFormaPgto]:checked', '#chechoutForm').val();

		$('#retornoResumoCarrinho').html('<?= $esperaResultado ?>');
		$('#retornoTotalCarrinho').html('<?= $esperaResultado ?>');

		var IDEndEntrega = $('#tipoEndEntrega').val();

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			postidendereco:IDEndEntrega,
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			posttipopagto:tipoPagto,
			posttipoedicao:'<?= md5("resumoCarrinho") ?>'
		},
		function(resultadoResumoCarrinho) {
			$('#retornoResumoCarrinho').html(resultadoResumoCarrinho);
		});

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			postidendereco:IDEndEntrega,
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			posttipopagto:tipoPagto,
			posttipoedicao:'<?= md5("totalCarrinho") ?>'
		},
		function(resultadoTotalCarrinho) {
			$('#retornoTotalCarrinho').html(resultadoTotalCarrinho);
		});        
	}    
    
	function alterarEndCarrinho() {
		var IDEndEntrega = $('#tipoEndEntrega').val();

		$('#enderecoEntregaCarrinho').html('<?= $esperaResultado ?>');
		$('#opcoesEntregaCarrinho').html('<?= $esperaResultado ?>');
		$('#retornoResumoCarrinho').html('<?= $esperaResultado ?>');
		$('#retornoTotalCarrinho').html('<?= $esperaResultado ?>');

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			postidendereco:IDEndEntrega,
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			posttipoedicao:'<?= md5("enderecoEntrega") ?>'
		},
		function(resultadoEndereco) {
			$('#enderecoEntregaCarrinho').html(resultadoEndereco);
		});

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			postidendereco:IDEndEntrega,
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			posttipoedicao:'<?= md5("opcoesEntrega") ?>'},
		function(resultadoOpcoes) {
			$('#opcoesEntregaCarrinho').html(resultadoOpcoes);
		});

		valoresCarrinho();
	}

function obterParcelasCartao() {
		var pgBandeira = $('#pgBandeira').val();

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			portidbandeira:pgBandeira,
			posttipoedicao:'<?= md5("opcoesPagto") ?>'
		},
		function(resultadoParcelas) {
				$('#pgParcela').html(resultadoParcelas);
		});
	}
    
	function opcoesEntrega(opcao) {
		var numOpcoes = $('#opNumOpcoes').val();
		var IDEndEntrega = $('#tipoEndEntrega').val();
		var opcoesSelecionadas = [];

		$('#resultadoServFrete').html('');

		for (i = 1; i <= numOpcoes; i++) {
			opcoesSelecionadas[i] = $('input[name=opEntrega' + i + ']:checked', '#chechoutForm').val();
		}

		$.post('/_pages/checkoutEditar.php', {
			postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
			localidadeTransporteID:opcoesSelecionadas,
			numeroOpcoes:numOpcoes,
			postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
			postidendereco:IDEndEntrega,
			posttipoedicao:'<?= md5("servicoFrete") ?>'
		},
	function(resultadoServicoFrete) {
			if (resultadoServicoFrete.substr(0,2) == "!!") {
				$('#resultadoServFrete').html(resultadoServicoFrete.substr(2));
			} else {
				valoresCarrinho();
				obterParcelasCartao();
			}
		});
	}

	function finalizarCompra() {
       //Pegando o Finger Print e inserindo outro input
          $("#CodRastreio").val($("#MeuCampoQueReceberaValor").val());
       // fim Finger
                
		var formaPgto = $('input[name=pgFormaPgto]:checked', '#chechoutForm').val();
		var hash;
		var finalizar = false;

		$('#botaoFinalizar').html('<span class="fa fa-circle-o-notch fa-spin fa-fw"></span> Finalizando...');
		$('#botaoFinalizar').prop("disabled", true);

	if (formaPgto == 'zero') {
			//hash = GerarHash();
			hash = '6345kjf9uj45j6o459ujfkjhg09frtjprtjo' //Inserido uma chave para fazer o checkout, pois malina não usa Moip

			if (hash.substr(0,2) == "!!") {
				$('#retornoCheckout').html('<p>' + hash.substr(2) + '</p>');
			} else {
				$('#retornoCheckout').html('');
				$('#pgHash').val(hash);
				finalizar = true;
			}
		} else {
			$('#retornoCheckout').html('');
			finalizar = true;
		}

		if (finalizar) {
                
			$.post('/_pages/checkoutEditar.php', $("#chechoutForm").serialize(),
			function(resultadoFinalizarCompra) {
				if (resultadoFinalizarCompra.substr(0,2) == "!!") {
					$('#retornoCheckout').html('<p>' + resultadoFinalizarCompra.substr(2) + '</p>');
					$('#botaoFinalizar').html('Finalizar a compra');
					$('#botaoFinalizar').prop("disabled", false);
				} else {
					$('#retornoFinalizarCompra').html(resultadoFinalizarCompra);
					$('#checkpointconf').html('<div class="circulo active"></div> Confirmação');
				}
			}); 
		} else {
			$('#botaoFinalizar').html('Finalizar a compra');
			$('#botaoFinalizar').prop("disabled", false);
		}
	}
</script>

<section class="ordem">
	<div class="container">
		<div id="retornoFinalizarCompra">
                    <form name="chechoutForm" id="chechoutForm" method="post" action="/checkout" autocomplete="off" onsubmit="false" onclick='pegarCodigoRastreio();'>
				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">1. ENDEREÇO PARA ENTREGA</div>
							<div class="panel-body">
								<label>Selecione o endereço</label>
								<div class="form-group input-icone">
									<select name="tipoEndEntrega" id="tipoEndEntrega" class="form-control" onchange="alterarEndCarrinho();">
										<?php
											foreach ((array) $enderecos['Enderecos'] as $endEntrega) {
												$selecionado = ($enderecoCarrinho['ID'] == $endEntrega['ID']) ? " SELECTED" : "";
												echo '<option value="' . $endEntrega['ID'] . '"' . $selecionado . '>' . $tipoEndereco[$endEntrega['Tipo']] . '</option>';
											}
										?>
									</select>
								</div>                        
								<div id="enderecoEntregaCarrinho"></div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default" id="accordion">
							<div class="panel-heading">2. OPÇÕES DE ENTREGA</div>
							<div class="panel-body ordem-pagamento-entrega" id="opcoesEntregaCarrinho"></div>
						</div>
						<div class="panel panel-default ordem-forma-pagamento">
							<div class="panel-heading">3. FORMA DE PAGAMENTO</div>
								<div class="panel-body" id="formaPgtoCarrinho">
									<?php
										if (isset($parBoleto) && is_numeric($parBoleto)) {
											$boletoHabititado = true;
									?>
										<div class="form-group">
											<label><input type="radio" name="pgFormaPgto" id="pgFormaPgto" value="2" onchange="valoresCarrinho();" checked> Boleto Bancário</label>
										</div>
									<?php
										} else {
											$boletoHabititado = false;
										}
									?>
									<div class="form-group">
										<label><input type="radio" name="pgFormaPgto" id="pgFormaPgto" value="zero" onchange="valoresCarrinho();" <?= ($boletoHabititado) ? "" : " checked" ?>> Cartão de crédito</label>
									</div>
									<div class="form-group">
										<select class="form-control" name="pgBandeira" id="pgBandeira" onchange="obterParcelasCartao();">
											<option value="" disabled selected>Bandeira do cartão</option>
											<?php
												foreach ((array) $bandeirasCartao as $bandeira) {
													$descLowerCase = strtolower($bandeira['DescricaoSite']);
													if (strpos($descLowerCase, "boleto") !== false) continue;

													echo "<option value=\"" . $bandeira['PagamentoMetodoFormaID'] . "\">" . $bandeira['DescricaoSite'] . "</option>";
												}
											?>
										</select>
									</div>                              
									<div class="form-group input-icone">
										<input type="text" name="pgNumCartao" id="pgNumCartao" class="form-control" placeholder="Número no cartão" maxlength="19" autocomplete="off" />
										<i class="glyphicon glyphicon-credit-card"></i>
									</div>
									<div class="form-group" id="parcelasCartao">
										<div class="input-group">
											<select class="form-control" name="pgParcela" id="pgParcela">
												<option value="" disabled selected>Número de parcelas</option>
											</select>
											<span class="input-group-addon">
												<a data-toggle="tooltip" data-placement="top" title="Por favor selecione a quantidade de parcelas para a sua compra.">
													<i class="glyphicon glyphicon-info-sign"></i>
												</a>
											</span>
										</div>
									</div>
									<div class="form-group">
											<input type="text" class="form-control" name="pgNomeCartao" id="pgNomeCartao" placeholder="Nome impresso no cartão" autocomplete="off" />
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-6">
											<div class="form-group">
												<select class="form-control" name="pgMedVenc" id="pgMedVenc">
													<option disabled selected>Mês</option>
													<?php
														for ($i = 1; $i <= count($meses); $i++) {
															echo "<option value=\"" . $i . "\">" . $meses[$i] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6">
											<div class="form-group">
												<select class="form-control" name="pgAnoVenc" id="pgAnoVenc">
													<option disabled selected>Ano</option>
													<?php
														for ($i = date("Y"); $i < (date("Y") + 11); $i++) {
															echo "<option value=\"" . $i . "\">" . $i . "</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<input name="pgCVC" id="pgCVC" type="password" class="form-control" placeholder="Código de segurança" maxlength="3" autocomplete="off" />
											<span class="input-group-addon">
												<a data-toggle="tooltip" data-placement="top" title="Por favor informe o código de segurança do cartão.">
													<i class="glyphicon glyphicon-info-sign"></i>
												</a>
											</span>
										</div>
									</div>
									<div class="form-group">
									<!--	<label><input type="checkbox" name="pgSalvarCartao" value="1"> Salvar cartão para a próxima compra!</label> -->
									</div>
									<!--<i>Para efetuar o pagamento, não há necessidade de salvar seu cartão. Esta função apenas facilita suas próximas compras com toda segurança.</i>  -->
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-heading">4. REVISÃO DO PEDIDO</div>
								<div class="panel-body ordem-pagamento-revisao">
									<ul>
										<?php
											foreach ((array) $carrinho['Itens'] as $item) {
										?>
											<li>
												<div class="row">
													<div class="col-xs-3">
														<img src="<?= $item['ProdutoImagemMobile'] ?>" title="<?= $item['ProdutoDescricao'] ?>"/>
													</div>
													<div class="col-xs-5">
														<p>
															<?= $item['ProdutoDescricao'] ?><br>
																Quantidade: <?= $item['Quantidade'] ?>
														</p>
													</div>
													<div class="col-xs-4">
														<div class="text-right">
															<?= formatar_moeda($item['ValorTotal']) ?>
														</div>
													</div>
												</div>
											</li>
										<?php } ?>
									</ul>
									<div class="ordem-pagamento-revisao-subtotal" id="retornoResumoCarrinho"></div>
								</div>                        
								<div class="panel-footer ordem-pagamento-revisao-total" id="retornoTotalCarrinho"></div>
							</div>  
						</div>
				</div>
                                <input type="hidden" name="codVoucher" id="codVoucher" value="<?= $carrinho['CodigoVoucher'] ?>">
				<input type="hidden" name="posttipoedicao" id="posttipoedicao" value="<?= md5("finalizarCompra") ?>">
				<input type="hidden" name="postidusuario" id="postidusuario" value="<?= $dadosLogin['ID'] ?>">
				<input type="hidden" name="postidcarrinho" id="postidcarrinho" value="<?= $dadosLogin['CarrinhoId'] ?>">
				<input type="hidden" name="pgHash" id="pgHash" value="0">
                                <input type="hidden" name ="CodRastreio" id="CodRastreio" value= "" />
                                <input type="hidden" id="MeuCampoQueReceberaValor" value="" />
                                <input type="hidden" name="pgSalvarCartao" id ="pgSalvarCartao" value="1" />
                               
			</form>
			<script type="text/javascript">
				alterarEndCarrinho();
			</script>
		</div>
	</div>
</section>
<!-- Clear Sale FingerPrint -->
<script> 
    (function (a, b, c, d, e, f, g) { 
        a['CsdpObject'] = e; a[e] = a[e] || function () {
            (a[e].q = a[e].q || []).push(arguments)
        }, a[e].l = 1 * new Date(); f = b.createElement(c),
        g = b.getElementsByTagName(c)[0]; f.async = 1; f.src = d; g.parentNode.insertBefore(f, g)
    })(window, document, 'script', '//device.clearsale.com.br/p/fp.js', 'csdp');
    csdp('app', '292d372537'); 
    csdp('outputsessionid', 'MeuCampoQueReceberaValor');
</script>


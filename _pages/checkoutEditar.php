<?php
   	$phpPost = filter_input_array(INPUT_POST);
	define('HoorayWeb', TRUE);
	include_once ("../p_settings.php");

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("enderecoEntrega")) {
		$endSelCarrinho = getRest(str_replace("{IDParceiro}", $phpPost['postidparceiro'], $endPoint['endcadastral']));

		foreach ((array) $endSelCarrinho['Enderecos'] as $endCarrinho) {
			if ($endCarrinho['ID'] == $phpPost['postidendereco']) {
				$dadosCarrinho = ["CarrinhoID" => $phpPost['postidcarrinho'], "CEP" => $endCarrinho['CEP']];
				$carrinhoComFrete = sendRest($endPoint['fretecarrinho'], $dadosCarrinho, "PUT");
?>
	<div class="form-group input-icone">
		<input type="text" class="form-control" name="endDestinatario" id="endDestinatario" placeholder="Nome" value="<?= $endCarrinho['Destinatario'] ?>" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endCEP" id="endCEP" placeholder="CEP" value="<?= $endCarrinho['CEP'] ?>" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endRua" id="endRua" value="<?= $endCarrinho['Logradouro'] ?>" placeholder="Endereço" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endNum" id="endNum" value="<?= $endCarrinho['Numero'] ?>" placeholder="Número" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endComp" id="endComp" value="<?= $endCarrinho['Complemento'] ?>" placeholder="Completemento" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endBairro" id="endBairro" value="<?= $endCarrinho['Bairro'] ?>" placeholder="Bairro" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endCidade" id="endCidade" value="<?= $endCarrinho['Cidade']['Nome'] ?>" placeholder="Cidade" disabled="disabled" />
	</div>
	<div class="form-group">
		<input type="text" class="form-control" name="endUF" id="endUF" value="<?= $endCarrinho['Cidade']['Estado']['Sigla'] ?>" placeholder="Referêmcia" disabled="disabled" />
	</div>       
<?php
				break;
			}
		}
	}

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("opcoesEntrega")) {
		$endSelCarrinho = getRest(str_replace("{IDParceiro}", $phpPost['postidparceiro'], $endPoint['endcadastral']));

		foreach ((array) $endSelCarrinho['Enderecos'] as $endCarrinho) {
			if ($endCarrinho['ID'] == $phpPost['postidendereco']) {
				$cepOpcao = $endCarrinho['CEP'];
				break;
			}
		}

    //Opções de entrega por seller
		$dadosServEntrega = ["CarrinhoID" => $phpPost['postidcarrinho'], "CEP" => $cepOpcao];
    $opcoesEntrega = sendRest($endPoint['servicoentrega'], $dadosServEntrega, "PUT");

    $opcoesFornecedor = [];
    foreach ((array) $opcoesEntrega as $opEntrega) // Agrupa por os seviços por seller
		{
			if (!array_key_exists($opEntrega['FornecedorID'], $opcoesFornecedor)) {
				$opcoesFornecedor[$opEntrega['FornecedorID']] = ["NomeFornecedor" => $opEntrega['NomeFornecedor'], "Opcoes" => []];
			}
			array_push($opcoesFornecedor[$opEntrega['FornecedorID']]['Opcoes'], $opEntrega);
    }
?>
	<p id="resultadoServFrete"></p>
<?php
	$i = 0;
	foreach ((array) $opcoesFornecedor as $opPorFornec) {
		$i++;
?>
	<p>
		<a data-toggle="collapse" data-parent="#accordion" href="#opcao-<?= $i ?>"><b class="caret"></b> Carrinho <?= $opPorFornec['NomeFornecedor'] ?></a>
	</p>
	<div id="opcao-<?= $i ?>" class="panel-collapse collapse">
		<ul>
			<?php
				$ii = 1;
				foreach ((array) $opPorFornec['Opcoes'] as $opcoes) {
			?>
				<li>
					<label>
						<div class="row">
							<div class="col-xs-5"><input type="radio" name="opEntrega<?= $i ?>" id="opEntrega<?= $i ?>" value="<?= $opcoes['LocalidadeTransporteID'] ?>" onchange="opcoesEntrega('opEntrega<?= $i ?>');" /> <?= $opcoes['DescricaoServico'] ?></div>
							<div class="col-xs-4"><?= (!empty($opcoes['FreteGratis'])) ? "Frete Grátis" : formatar_moeda($opcoes['ValorCobrarCliente']) ?></div>
							<div class="col-xs-3 text-right"><?= date_format(date_create($opcoes['DataEntrega']), "d/m/y") ?></div>
						</div>
					</label>
				</li>
			<?php
					$ii ++;
				}
			?>
		</ul>
	</div>
<?php } ?>
	<input type="hidden" name="opNumOpcoes" id="opNumOpcoes" value="<?= $i ?>">
	<p>Seu pedido será liberado logo após a confirmação do pagamento.</p>    
<?php    
	}

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("opcoesPagto")) {
		$carrinho = getRest(str_replace('{IDCarrinho}', $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));

		$parcelamento = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinho['Total']], $endPoint['parcarrinho']));

		echo "<option disabled selected>Número de parcelas</option>";
		foreach ((array) $parcelamento as $parcela) {
			if ($parcela['Numero'] == 0 || $parcela['PagamentoMetodoFormaID'] != $phpPost['portidbandeira']) continue;

			echo "<option value=\"" . $parcela['Numero'] . "\">" . $parcela['Numero'] . "x sem juros de " . formatar_moeda($parcela['Valor']) . "</option>";
		}    
	}

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("resumoCarrinho")) {
		$carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));

		$parcelamentoSubTotal = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinho['SubTotal']], $endPoint['parcarrinho']));

		$indexBoleto = array_search("0", array_column($parcelamentoSubTotal, 'Numero'));

		$parcelaBoleto = $parcelamentoSubTotal[$indexBoleto];
?>
	<div class="row">
		<div class="col-sm-6">Subtotal: </div>
		<div class="col-sm-6 text-right"><?= formatar_moeda($carrinho['SubTotal']) ?></div>
	</div>
<?php
	if (!empty($phpPost['posttipopagto']) && $phpPost['posttipopagto'] == "2") {
?>
	<div class="row">
		<div class="col-sm-6">Desconto boleto: </div>
		<div class="col-sm-6 text-right">- <?= formatar_moeda($carrinho['SubTotal'] - $parcelaBoleto['Valor']) ?></div>
	</div>
<?php } ?>
	<div class="row">
		<div class="col-sm-6">Entrega: </div>
		<div class="col-sm-6 text-right"><?= (!empty($carrinho['Frete'] && is_numeric($carrinho['Frete']))) ? formatar_moeda($carrinho['Frete']) : formatar_moeda(0) ?></div>
	</div>
        
        <div class="row">
		<div class="col-sm-6">Desconto Voucher: </div>
		<div class="col-sm-6 text-right"><?= (!empty($carrinho['ValorDesconto'] && is_numeric($carrinho['ValorDesconto']))) ? formatar_moeda($carrinho['ValorDesconto']) : formatar_moeda(0) ?></div>
	</div>
<?php
	}
      
	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("totalCarrinho")) {
		$carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));

		if (!empty($phpPost['posttipopagto']) && $phpPost['posttipopagto'] == "2") // se boleto, exibe total com desconto
		{
			$parcelamentoSubTotal = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinho['SubTotal']], $endPoint['parcarrinho']));

			$indexBoleto = array_search("0", array_column($parcelamentoSubTotal, 'Numero'));

			$parcelaBoleto = $parcelamentoSubTotal[$indexBoleto];

			$valorTotalCarrinho = ($parcelaBoleto['Valor'] + $carrinho['Frete']) - $carrinho['ValorDesconto'];
		} else {
			$valorTotalCarrinho = $carrinho['Total'];
		}
?>
	<div class="row">
		<div class="col-xs-4">
			&nbsp;&nbsp;Total:
		</div>
		<div class="col-xs-8">
			<div class="ordem-pagamento-revisao-total-valor">
				<?= formatar_moeda($valorTotalCarrinho) ?>&nbsp;&nbsp;<br /><br />
			</div>
		</div>
	</div>
	<div id="retornoCheckout"></div>
	<div class="ordem-botao-finalizar-bottom">
		<button type="button" onclick="finalizarCompra();" class="btn btn-lg btn-primary" id="botaoFinalizar">Finalizar a compra</button>
	</div
<?php
	}

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("servicoFrete")) {
		if (!empty($phpPost['localidadeTransporteID'])) {
			$endOpcaoEntrega = getRest(str_replace("{IDParceiro}", $phpPost['postidparceiro'], $endPoint['endcadastral']));

			foreach ((array) $endOpcaoEntrega['Enderecos'] as $endereco) {
				if ($endereco['ID'] == $phpPost['postidendereco']) {
					$cepServicoEntrega = $endereco['CEP'];
					break;
				}
			}

			$dadosServEntrega = ["CarrinhoID" => $phpPost['postidcarrinho'], "CEP" => $cepServicoEntrega];
			$opcoesEntrega = sendRest($endPoint['servicoentrega'], $dadosServEntrega, "PUT");

			$servicosFrete = [];
			foreach ((array) $phpPost['localidadeTransporteID'] as $opcaoEntrega) {
			if (!empty($opcaoEntrega)) {
					$indexServico = array_search($opcaoEntrega, array_column($opcoesEntrega, 'LocalidadeTransporteID'));

					if (isset($indexServico) && is_numeric($indexServico)) {
						array_push($servicosFrete, $opcoesEntrega[$indexServico]);
					}
				}
			}

		$atualizacaoServicoEntrega = sendRest($endPoint['atualizarfrete'], $servicosFrete, "PUT");

			if (empty($atualizacaoServicoEntrega)) {
				echo "!!Erro ao atualizar serviço de entrega.";
			}
		}
	}

	if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("finalizarCompra")) {
		session_start();
		$dadosLoginCK = login($endPoint['login'], $_SESSION['bearer']);
    
		if (empty($dadosLoginCK['ID'])) {
			echo "!!Sua sessão expirou. Por favor efetue login novamente para concluir a sua compra.";
			die;
		}

		$enderecosCK = getRest(str_replace("{IDParceiro}", $dadosLoginCK['Parceiro']['ID'], $endPoint['endcadastral']));
    
	 $carrinhoCK = getRest(str_replace('{IDCarrinho}', $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
    
		for ($i = 1; $i <= $phpPost['opNumOpcoes']; $i++) {
			if (empty($phpPost['opEntrega' . $i])) {
				echo "!!Escolha as opções de entrega para as marcas do seu carrinho.";
				die;
			}
		}
    
		if (empty($phpPost['pgFormaPgto'])) {
			echo "!!Escolha a forma de pagamento.";
			die;
		}
    
		if ($phpPost['pgFormaPgto'] == "zero") // cartao de credito
		{
			if (empty($phpPost['pgBandeira']) || trim($phpPost['pgBandeira']) == "") {
				echo "!!Selecione a bandeira do cartão de crédito.";
				die;
			}

			if (empty($phpPost['pgNumCartao']) || trim($phpPost['pgNumCartao']) == "") {
				echo "!!Informe o número do cartão de crédito.";
				die;
			}

			if (empty($phpPost['pgParcela']) || trim($phpPost['pgParcela']) == "") {
				echo "!!Selecione o número de parcelas.";
				die;
			}

			if (empty($phpPost['pgNomeCartao']) || trim($phpPost['pgNomeCartao']) == "") {
				echo "!!Informe o nome impresso no cartão de crédito.";
				die;
			}

			if (empty($phpPost['pgMedVenc']) || trim($phpPost['pgMedVenc']) == "") {
				echo "!!Selecione o mes de vencimento do cartão de crédito.";
				die;
			}

			if (empty($phpPost['pgAnoVenc']) || trim($phpPost['pgAnoVenc']) == "") {
				echo "!!Selecione o ano de vencimento do cartão de crédito.";
				die;
			}

			if (empty($phpPost['pgCVC']) || trim($phpPost['pgCVC']) == "") {
				echo "!!Informe o código de segurança do cartão de crédito.";
				die;
			}
		}
    
		$dadosPedido = [
			"LoginID" => $dadosLoginCK['ID'],
			"EnderecoID"  => $phpPost['tipoEndEntrega'],
			"PagamentoMetodoFormaID" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgBandeira'] : "6", // TODO trazer numero da parcela do boleto
			"ClassificacaoPagto" => 0, // para mkt place, sempre 0 ($phpPost['pgFormaPgto'] == "zero") ? 0 : $phpPost['opNumOpcoes'],
			"Parcela" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgParcela'] : "1",
			"CarrinhoID"  => $phpPost['postidcarrinho'],
			"CupomDesconto"  => $phpPost['codVoucher'],
			"DadosCartao"  => [
				"Titular" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgNomeCartao'] : "",
				"Numero" => ($phpPost['pgFormaPgto'] == "zero") ? str_replace(" ", "", $phpPost['pgNumCartao']) : "",
				"Mes" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgMedVenc'] : "", 
				"Ano" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgAnoVenc'] : "",
				"CodigoSeguranca" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgCVC'] : "",
				"Hash" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgHash'] : "",
                                "CodigoRastreio" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['CodRastreio'] : "",
                                "SalvarCartao" => ($phpPost['pgFormaPgto'] == "zero") ? $phpPost['pgSalvarCartao'] : ""
			],
		];
               var_dump($dadosPedido);
               $finalizarPedido = sendRest($endPoint['checkout'], $dadosPedido, "POST");
             		if (empty($finalizarPedido['Gravou'])) {
			echo "!!";

			if (!is_array($finalizarPedido) && !empty($finalizarPedido)) {
				echo "<br>" . str_replace(chr(13), "<br>", $finalizarPedido);
			} else {
				echo "Erro ao gravar o pedido. <br>Por favor tente novamente.";
			}
			die;
		}

		if (isset($_SESSION['carrinho'])) {
			unset($_SESSION['carrinho']);
		}

		$indexEndereco = array_search($phpPost['tipoEndEntrega'], array_column($enderecosCK['Enderecos'], 'ID'));

		$enderecoPedido = $enderecosCK['Enderecos'][$indexEndereco];
?>
	<section class="ordem">
		<div class="container">
			<h3 class="text-center">
				<i class="glyphicon glyphicon-ok"></i> <?= (empty($finalizarPedido['RetornoGateway']) || !empty($finalizarPedido['RetornoGateway']['Erro'])) ? "Erro na conclusão do seu pedido." : "Seu pedido foi concluído com sucesso!<br>" ?> 
			</h3>
			<div class="row">
				<div class="<?=  (empty($finalizarPedido['RetornoGateway']) || !empty($finalizarPedido['RetornoGateway']['Erro'])) ? "col-md-12" : "col-md-6" ?> col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">FORMA DE PAGAMENTO</div>
						<div class="panel-body">
							<div class="ordem-pagamento-concluido-forma">
								<?php
									if (empty($finalizarPedido['RetornoGateway']) || !empty($finalizarPedido['RetornoGateway']['Erro'])) {
										echo "Problemas com a transação do pagamento.";

										if (!empty($finalizarPedido['RetornoGateway']['Mensagem'])) {
											echo "<span>Mais detalhes: " . $finalizarPedido['RetornoGateway']['Mensagem'] . "</span>";
										} else {
											echo "<span>Mais detalhes: Ocorreu um erro no processamento do pagamento do seu pedido.</span>";
										}

										echo "<div><a href=\"/checkout\" class=\"btn btn-lg btn-primary\">Tentar novamente</a></div>";
									} else {
								?>
								<?= ($phpPost['pgFormaPgto'] == "zero") ? "Cartão de crédito. " : "Boleto bancário. " ?>
								<?php
									if (($phpPost['pgFormaPgto'] == "zero")) {
										echo "<span>Valor total do pedido: " . formatar_moeda($carrinhoCK['Total']) . "</span>";
										echo "<span>" . $phpPost['pgParcela'] . "x sem juros de " . formatar_moeda($carrinhoCK['Total'] / $phpPost['pgParcela']) . "</span>";
									} else {
										$parcelas = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinhoCK['SubTotal']], $endPoint['parcarrinho']));
										$indexBoleto = array_search("0", array_column($parcelas, 'Numero')); // busca pela parcela 0 (boleto)
                                                                                
                                                                                $valorBoleto = $parcelas[$indexBoleto]['Valor'] + $carrinhoCK['Frete']- ($carrinhoCK['ValorDesconto'] + $parcelaBoleto['Valor']);
										echo "<span>Valor total do pedido: " . formatar_moeda($valorBoleto) . "</span>";
										echo "<div><a href=\"" . $finalizarPedido['RetornoGateway']['Redirect'] . "/print\" target=\"_blank\" class=\"btn btn-lg btn-primary\">Baixar o boleto</a></div>";
									}
								?> 
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php
					if (!empty($finalizarPedido['RetornoGateway']) && empty($finalizarPedido['RetornoGateway']['Erro'])) {
				?>            
					<div class="col-md-6 col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading">PEDIDO № <?= $finalizarPedido['ID'] ?></div>
							<div class="panel-body ordem-pagamento-concluido-pedido">
								Acabamos de enviar os detalhes do pedido para:
								<span><?= $dadosLoginCK['Email'] ?></span>
								Endereço de entrega
								<span>
									<?= $enderecoPedido['Logradouro'] ?>, <?= $enderecoPedido['Numero'] ?> <?= (!empty($enderecoPedido['Complemento'])) ? " - " . $enderecoPedido['Complemento'] : "" ?><br />
									<?= $enderecoPedido['CEP'] ?> - <?= $enderecoPedido['Bairro'] ?><br />
									<?= $enderecoPedido['Cidade']['Nome'] ?> - <?= $enderecoPedido['Cidade']['Estado']['Sigla'] ?>
								</span>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>

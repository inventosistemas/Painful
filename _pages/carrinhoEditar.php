<?php
	$phpPost = filter_input_array(INPUT_POST);
	define('HoorayWeb', TRUE);
	include_once ("../p_settings.php");
	if (!empty($phpPost['postcarrinho']) && $phpPost['postcarrinho'] == md5("addCarrinho")) {
		if (empty($phpPost['postqdteproduto']) || !is_numeric($phpPost['postqdteproduto'])) {
			die ("!!Por favor informe a quantidade.");
		}
		$dadosProdCarrinho = getRest(str_replace("{IDProduto}", $phpPost['postidproduto'], $endPoint['produto']));
		$skusProduto = $dadosProdCarrinho['Skus'];
		if (count($skusProduto) == 1) {
			$skuCarrinho = $skusProduto[0]['ID'];
		} else {
			$descritorFiltro = ($phpPost['postdescritor'] == -1) ? null : $phpPost['postdescritor'];
			$descritorFiltro2 = ($phpPost['postdescritor2'] == -1) ? null : $phpPost['postdescritor2'];
			$descritorFiltro3 = ($phpPost['postdescritor3'] == -1) ? null : $phpPost['postdescritor3'];
			$descritorFiltro4 = ($phpPost['postdescritor4'] == -1) ? null : $phpPost['postdescritor4'];
			$descritorFiltro5 = ($phpPost['postdescritor5'] == -1) ? null : $phpPost['postdescritor5'];
			foreach ((array) $skusProduto as $skubusca) {
				if ($skubusca['Descritor'] == $descritorFiltro &&
						$skubusca['Descritor2'] == $descritorFiltro2 &&
						$skubusca['Descritor3'] == $descritorFiltro3 &&
						$skubusca['Descritor4'] == $descritorFiltro4 &&
						$skubusca['Descritor5'] == $descritorFiltro5)
				{
					$skuCarrinho = $skubusca['ID'];
					break;
				}
			}
		}
		$itemExiste = -1;
		if ($phpPost['postidcarrinho'] > 0) {
			$verificarItemNoCarrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
			foreach ((array) $verificarItemNoCarrinho['Itens'] as $itemVerificar) {
				if ($itemVerificar['SkuID'] == $skuCarrinho) {
					$itemExiste = $itemVerificar['Id'];
					break;
				}
			}
		}
		if ($itemExiste > 0) // Caso já exista no carrinho, retira-o e o adicina com a nova quantidade.
		{
			$removerDoCarriho = sendRest(str_replace("{IDItem}", $itemExiste, $endPoint['delcarrinho']), [], "DELETE");
		}
		$dadosCarrinho = ["CarrinhoID" => ($phpPost['postidcarrinho'] > 0) ? $phpPost['postidcarrinho'] : NULL, "SkuID" => $skuCarrinho, "Quantidade" => $phpPost['postqdteproduto']];
		$retrornoCarrinho = sendRest($endPoint['addcarrinho'], $dadosCarrinho, "POST");
		if (!empty($retrornoCarrinho['Erro']) && $retrornoCarrinho['Erro'] == true) {
			die ("!!" . $retrornoCarrinho['Mensagem']);
		}
		if ($phpPost['postidlogin'] > 0) {
			$dadosLoginCarrinho = ["CarrinhoID" => $retrornoCarrinho['Dados']['CarrinhoID'], "LoginID" => $phpPost['postidlogin']];
			$associarCarrinho = sendRest($endPoint['addlogincarrinho'], $dadosLoginCarrinho, "PUT");
		} else {
			session_start();
			$_SESSION['carrinho'] = $retrornoCarrinho['Dados']['CarrinhoID'];
		}
		$carrinho = getRest(str_replace("{IDCarrinho}", $retrornoCarrinho['Dados']['CarrinhoID'], $endPoint['obtercarrinho']));
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-73086747-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-73086747-1');
</script>
	<div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</div>
	<div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>
	<div class="title-modal">
		<h2>Carrinho</h2>
	</div>
	<div class="cart-preview">
		<ul>
			<?php foreach ((array) $carrinho['Itens'] as $itemCarrinho) { ?>
				<li id="itemCarinhoModal<?= $itemCarrinho['Id'] ?>">
					<div class="row">
						<div class="col-xs-3">
							<img src="<?= $itemCarrinho['ProdutoImagemMobile'] ?>" title="<?= $itemCarrinho['ProdutoDescricao'] ?>" />
						</div>
						<div class="col-xs-7">
							<p class="title"><?= $itemCarrinho['ProdutoDescricao'] ?></p>
							<p class="brand"><?= $itemCarrinho['Marca'] ?></p>
							<p class="qtd">Quantidade: <?= $itemCarrinho['Quantidade'] ?></p>
							<p class="value"><?= formatar_moeda($itemCarrinho['ValorTotal']) ?></p>
							<p><i id="resultDelCarrinho<?= $itemCarrinho['Id'] ?>"></i></p>
						</div>
						<div class="col-xs-2 text-right">
							<a href="javascript:retirarCarrinhoModal('<?= $itemCarrinho['Id'] ?>');" title="Retirar do carrinho" class="btn-remove"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
		<a href="/carrinho#cupomLink" class="m-coupon">Cupom de Desconto</a>
		<form method="post" action="/carrinho" class="form-btn">
			<button type="submit" class="btn btn-primary btn-lg">Checkout</button>
		</form>
	</div>
<?php    
	}
	if (!empty($phpPost['postcarrinho']) && $phpPost['postcarrinho'] == md5("editCarrinho")) {
		if ($phpPost['posttipoedicao'] == md5("remover") && empty($phpPost['postidproduto'])) {
			echo "!!Produto não encontrado no carrinho.";
			die;
		}
		if ($phpPost['posttipoedicao'] == md5("remover")) {
			$removerItem = sendRest(str_replace("{IDItem}", $phpPost['postidproduto'], $endPoint['delcarrinho']), [], "DELETE");
		} elseif ($phpPost['posttipoedicao'] == md5("alterarqdte")) {
			$dadosQtde = ["CarrinhoItemID" => $phpPost['postidproduto'], "Quantidade" => $phpPost['postqtdeproduto']];
			$atualizarQuantidade = sendRest($endPoint['qtdecarrinho'], $dadosQtde, "PUT");
		}
		if ($phpPost['posttipocarrinho'] == md5("modal")) {
			$carrinhoCount = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
			if (!empty($carrinhoCount['Itens'])) {
				echo count($carrinhoCount['Itens']);
			} else {
				echo " ";
			}
			//echo "Atualizando o carrinho...";
		} elseif ($phpPost['posttipocarrinho'] == md5("pagina")) {
			if ($phpPost['posttipoedicao'] == md5("calcularCEP")) {
				$CEPCalculo = preg_replace('/\D/', '', $phpPost['postcepcarrinho']);
				$dadosCEPCarrinho = ["CarrinhoID" => $phpPost['postidcarrinho'], "CEP" => $CEPCalculo];
				$carrinho = sendRest($endPoint['fretecarrinho'], $dadosCEPCarrinho, "PUT");
				if (!empty($carrinho['Message'])) {
					$carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
				}
			} else {
				$carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
			}
			$itensFornecedor = [];
			foreach ((array) $carrinho['Itens'] as $item) // agrupa itens por fornecedor
			{
				if (!array_key_exists($item['Fornecedor'], $itensFornecedor)) {
					$itensFornecedor[$item['Fornecedor']] = ["Fornecedor" => $item['Fornecedor'], "Itens" => []];
				}
				array_push($itensFornecedor[$item['Fornecedor']]['Itens'], $item);
			}
			foreach ((array) $itensFornecedor as $fornecedor) {
?>
	<div class="panel-heading">Carrinho Malina <span>(<?= count($fornecedor['Itens']) ?> <?= (count($fornecedor['Itens']) > 1) ? "Itens" : "Item" ?>)</span></div>
<?php
	$i = 0;
	foreach ((array) $fornecedor['Itens'] as $itemFornecedor) {
		if ($i != 0) echo "<div class=\"divisor-cart\"></div>";
?>
	<div class="box-item">
		<div class="cart-lf">
			<div class="box-img">
				<img src="<?= $itemFornecedor['ProdutoImagemMobile'] ?>" title="<?= $itemFornecedor['ProdutoDescricao'] ?>">
			</div><!--
			--><div class="box-info">
				<p class="name"><?= $itemFornecedor['ProdutoDescricao'] ?></p>
				<!--<p class="provider">Vendido e entregue por:</span> <?= $itemFornecedor['Fornecedor'] ?></p> -->
				<p class="sku">ID SKU:</span> <?= $itemFornecedor['SkuID'] ?></p>
				<p class="price-unit">Valor unitário: <span><?= formatar_moeda($itemFornecedor['ValorUnit']) ?></span></p>
			</div>
			<a href="javascript:retirarCarrinho('<?= $itemFornecedor['Id'] ?>');" class="glyphicon glyphicon-trash"><span>Excluir</span></a>
		</div>
		<div class="cart-ct">
			<form class="form-qtd" name="qdtProd" id="qdtProd" method="post" onsubmit="false">
				<div class="inner-qtd">
					<label for="qtdeItemCarriho<?= $itemFornecedor['Id'] ?>">Quantidade</label>
					<input type="number" name="qtdeItemCarriho<?= $itemFornecedor['Id'] ?>" id="qtdeItemCarriho<?= $itemFornecedor['Id'] ?>" value="<?= $itemFornecedor['Quantidade'] ?>" class="textbox" min="1">
					<a href="javascript:atualizarQtde('<?= $itemFornecedor['Id'] ?>');"><span> Atualizar</span></a>
				</div>
			</form>
		</div>
		<div class="cart-rt box-value">
			<p id="totalitem">Total item: <span><?= formatar_moeda($itemFornecedor['ValorTotal']) ?></span></p>
			 <?php
                        $condicao = is_numeric($itemFornecedor['ValorFrete'])? formatar_moeda($itemFornecedor['ValorFrete']) : "Não calculado" ;
                        if($itemFornecedor['ValorFrete'] == 0 && $condicao <> "Não calculado")
                        {
                            $texto = "Frete Grátis: ";
                            $cor = "color:#ff0000";
                        }
                        else 
                        {
                            $texto = "Frete: ";
                        }
                        ?>
			<p class="shipping" style="<?=$cor?>" ><?=$texto ?><?= (is_numeric($itemFornecedor['ValorFrete'])) ? formatar_moeda($itemFornecedor['ValorFrete']) : "Não calculado" ?></p>
			<p class="time">Previsão de entrega: <?= date_format(date_create($itemFornecedor['DataEntrega']), "d/m/Y") ?></p>
		</div>
	</div>
<?php
			$i ++;
		}
	}
?>
	<!-- footer panel -->
	<div class="panel-footer cart-order">
		<div class="row">
			<div class="col-xs-4 hidden-xs">
				<h3 class="t-order"><a id="cupomLink">Resumo do carrinho</a></h3>
			</div><!--
			--><div class="col-xs-12 col-sm-8">
				<div class="order-total">
					<p class="t-itens">Itens: <span><?= formatar_moeda($carrinho['SubTotal']) ?></span></p>
					<p class="t-shipping" style="<?=$cor?>" ><?=$texto?><?= (is_numeric($carrinho['Frete'])) ? formatar_moeda($carrinho['Frete']) : "Não calculado" ?></p>
					<p class="t-discount">Desconto: <?= formatar_moeda($carrinho['ValorDesconto']) ?></p>
					<p class="t-price">Total: <span><?= formatar_moeda($carrinho['Total']) ?></span></p>
				</div>
			</div>
		</div>
	</div>   
<?php        
		}
	}
	
	if (!empty($phpPost['postcarrinho']) && $phpPost['postcarrinho'] == md5("countCarrinho")) {
		if (!empty($phpPost['postidcarrinho'])) {
			$carrinhoCount = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
			if (!empty($carrinhoCount['Itens'])) {
				echo count($carrinhoCount['Itens']);
			} else {
				echo "0";
			}
		}
	}
?>
<?php

	if (empty($tipoBusca)){
		include_once ("/_pages/404.php");
		die;
	}

	if ($tipoBusca == "busca" && empty($termoBusca)) {
?>    
	<div class="resultado-busca">
		<h3>Por favor informe um termo para a busca.</h3>
	</div>
<?php
	} else {
		$esperaResultado = '<ul id="itensGrid"><li><span class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></span></li></ul>';
    
		switch ($tipoBusca) {
			case "busca": 
				$postBusca = $termoBusca; 
				break;

			case "secao":
				$postBusca = $IDSecao; 
				break;

			case "marca":
				$postBusca = $IDMarca; 
				break;

			case "categoria":
				$postBusca = $IDCategoria; 
				break;        
		}

		$menuDescricaoSecao = '';
		$idDoMenuSecao = (int) $postBusca;
		foreach ((array) $menuSite as $menusSite) {
			foreach ((array) $menusSite['SecaoID'] as $menuID) {
				if ($menuID == $idDoMenuSecao) {
					$menuDescricaoSecao = $menusSite['Descricao'];
				}
			}
		}
		$infoBreadcrumb = [
			"busca" => ["URL" => "/busca",
			"Descricao" => "Busca"],
			"secao" => ["URL" => "/",
			"Descricao" => (!empty($menuDescricaoSecao))? $menuDescricaoSecao : "" ],
			"categoria" => ["URL" => "/",
			"Descricao" => (!empty($categoriaSite['Descricao'])) ? $categoriaSite['Descricao'] : ""],
			"marca" => ["URL" => "/marcas",
			"Descricao" => (!empty($detalheMarca['Descricao'])) ? $detalheMarca['Descricao'] : ""]
		];
		$dadosBuscaCat = [
			"Chave" => ($tipoBusca == "busca") ? $termoBusca : "",
			"Count" => "-1",
			"UltimoID" => -1,
			"UltimoPreco" => -1,
			"UltimaDescricao" => "",
			"TipoOrdenacao" => 0,
			"ProdutoID" => -1,
			"SecaoID" => ($tipoBusca == "secao") ? $IDSecao : -1,
			"MarcaID" => ($tipoBusca == "marca") ? $IDMarca : -1,
			"ProdutoCategoriaID" => ($tipoBusca == "categoria") ? $IDCategoria : -1,
			"ProdutoSubCategoriaID" => -1
		];
		$resultadoBuscaCat = sendRest($endPoint['buscaestendida'], $dadosBuscaCat, "POST");
		$filtros = [];
		$precos = [];
    
		foreach ((array) $resultadoBuscaCat as $produtoBuscaCat) {
			foreach ((array) $produtoBuscaCat['Caracteristicas'] as $caracteristica) {
				if (!$caracteristica['Filtro']) continue; // só adiciona filtros marcados com TRUE

				if (empty($caracteristica['Posicao'])) {
					$caracteristica['Posicao'] = 999999999; // não definida posição
				}

				if (!array_key_exists($caracteristica['TipoID'], $filtros)) //cria a chave com o tipo, se nao existir
				{
					$filtros[$caracteristica['TipoID']] = [
						"TipoID" => $caracteristica['TipoID'],
						"ValorID" => $caracteristica['ValorID'],
						"Descricao" => $caracteristica['Descricao'],
						"Posicao" => $caracteristica['Posicao'],
						"Opcoes" => []
					];
				}

				if (!array_key_exists($caracteristica['ValorID'], $filtros[$caracteristica['TipoID']]['Opcoes'])) // cria o valor, se nao houver
				{
					$opcaoFiltro = [
						"ValorID" => $caracteristica['ValorID'],
						"Valor" => $caracteristica['Valor']
					];
					$filtros[$caracteristica['TipoID']]['Opcoes'][$caracteristica['ValorID']] = $opcaoFiltro;
				}
			}
			array_push($precos, $produtoBuscaCat['PrecoVigente']);
		}
		$minPreco = (!empty($precos) && is_array($precos)) ? floor(min($precos)) : 0;
		$maxPreco = (!empty($precos) && is_array($precos)) ? ceil(max($precos)) : 0;
?>

<?php if ($tipoBusca == "categoria") { ?>
	<section class="categoria-foto-principal">
		<div class="container-fluid" style="background-image: url(<?= $categoriaSite['Imagem'] ?>);">
			<div class="vertical-center categoria-foto-titulo"><?= $categoriaSite['Descricao'] ?></div>
		</div>
	</section>    
<?php
	}
	if ($tipoBusca == "marca") {
?>
	<section class="vitrine-foto-principal">
		<div class="container">
			<img src="<?= $detalheMarca['Imagem'] ?>" class="img-responsive" title="<?= $detalheMarca['Descricao'] ?>"/>
		</div>
	</section>
<?php
	}
?>
<section class="content-breadcrumb">
	<div class="container">
		<div class="box-breadcrumb">
			<a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
			<?= $infoBreadcrumb[$tipoBusca]['Descricao'] ?> <span class="glyphicon glyphicon-menu-right"></span>
			<span class="page-title" id="countBusca"><?= (!empty($resultadoBuscaCat)) ? count($resultadoBuscaCat) : "0" ?></span><span class="page-title"> produtos encontrados</span>
		</div>
	</div>
</section>

<script type="text/javascript">
	function limparFiltro() {
		$('#searchGrid').html('<?= $esperaResultado ?>');

		document.getElementById('searchFilter').reset();
		$('#postvalormin').val(<?= $minPreco ?>);
		$('#postvalormax').val(<?= $maxPreco ?>);

		$.post('/_pages/filtro.php', {
				posttipobusca: '<?= $tipoBusca ?>',
				posttermobusca: '<?= $postBusca ?>',
				posttipofiltro: '<?= md5("buscaProduto") ?>'
			},
			function (resultadoBusca) {
				$('#searchGrid').html(resultadoBusca);

				var item = ' item';
				if ($(resultadoBusca).children().length > 1) {
					item = ' itens';
				}

				$('#countResult').html($('#searchGrid ul li').length + item);
				snapSlider.noUiSlider.set([<?= $minPreco ?>, <?= $maxPreco ?>]);
			});
	}

	function filtrarBusca(ordenacao) {
		if (ordenacao >= 0) {
			document.searchFilter.postordenacao.value = ordenacao;
		}

		$('#searchGrid').html('<?= $esperaResultado ?>');

		$('#countResult').html('Filtrando...');

		$.post('/_pages/filtro.php', $("#searchFilter").serialize(),
			function (resultadoBusca) {
				$('#searchGrid').html(resultadoBusca);

				var item = ' item';
				if ($(resultadoBusca).children().length > 1) {
					item = ' itens';
				}

				//$('#countResult').html($(resultadoBusca).children().length + item);
				$('#countResult').html($('#searchGrid ul li').length + item);
			});
	}
</script>
    
<?php if (!empty($resultadoBuscaCat)) { ?>
	<div class="container">
		<div class="content-sidebar">
			<div class="title-filter">
				<h4>Filtros</h4>
				<span id="countResult"><?= (!empty($resultadoBuscaCat)) ? count($resultadoBuscaCat) : "0" ?> itens</span>
				<button type="button" onclick="limparFiltro()" class="clear-filter">Limpar Filtros</button>
			</div>
			<div class="drop-toggle filter-toggle"><span class="nav-plus"></span><span>Ver filtros</span></div>
			<div class="panel-group drop-content side-filter">
				<form name="searchFilter" id="searchFilter" method="post" action="/" onsubmit="false">
					<?php if (!empty($precos)) : ?>
						<div class="panel">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#side-filter-size" class="tab-toggle collapsed">
										<span>Faixa de preço</span>
									</a>
								</h4>
							</div>
							<div id="side-filter-size" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="handles-wrap">
										<div id="slider-handles"></div>
										<div class="range-value">
											<p class="max-val">R$ <span id="slider-snap-value-lower"></span>,00</p>
											<p class="until"><span id="slider-snap-value-until">até</span></p>
											<p class="min-val">R$ <span id="slider-snap-value-upper"></span>,00</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php
						function odernacaoOpcoes($a, $b) {
							return strcmp($a['Valor'], $b['Valor']);
						}
						function odernacaoFiltro($a, $b) {
							if ($a['Posicao'] == $b['Posicao']) {
								return 0;
							}
							return ($a['Posicao'] < $b['Posicao']) ? -1 : 1;
						}
						if (!empty($filtros)) {
							usort($filtros, "odernacaoFiltro"); // ordenas as caracteristicas por ordem alfabetica
						}
						$i = 0;
						foreach ((array) $filtros as $filtro) {
					?>
						<div class="panel">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#side-filter-<?= $i ?>" class="tab-toggle collapsed">
										<span><?= $filtro['Descricao'] ?></span>
									</a>
								</h4>
							</div>
							<div id="side-filter-<?= $i ?>" class="panel-collapse collapse">
								<div class="panel-body">
									<?php
										if (!empty($filtro['Opcoes'])) {
											usort($filtro['Opcoes'], "odernacaoOpcoes"); // ordenas as opções
										}
										foreach ((array) $filtro['Opcoes'] as $opcao) {
									?>
										<label><input type="checkbox" name="<?= $filtro['TipoID'] . "_" . $opcao['ValorID'] ?>" value="<?= $filtro['TipoID'] . '##' . $opcao['ValorID'] ?>" onclick="filtrarBusca(-1);"><span><?= $opcao['Valor'] ?></span></label>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php $i ++; } ?>
					<input type="hidden" name="posttermobusca" id="posttermobusca" value="<?= $postBusca ?>">
					<input type="hidden" name="posttipofiltro" id="posttipofiltro" value="<?= md5("buscaProduto") ?>">
					<input type="hidden" name="posttipobusca" id="posttipobusca" value="<?= $tipoBusca ?>">
					<input type="hidden" name="postordenacao" id="postordenacao" value="-1">
					<input type="hidden" name="postvalormin" id="postvalormin" value="<?= $minPreco ?>">
					<input type="hidden" name="postvalormax" id="postvalormax" value="<?= $maxPreco ?>">
				</form>
			</div>
		</div>
		<div class="content-right">
			<section class="content-showcase">
				<div class="box-order">
					<label for="orderSearch">Ordenar por:</label>
					<select name="order" id="orderSearch">
						<option value="">Selecione</option>
						<option value="0;">Descrição A-Z</option>
						<option value="3">Descrição Z-A</option>
						<option value="4">Lançamento</option>
						<option value="2">Menor preço</option>
						<option value="1">Maior preço</option>
						<option value="5">% OFF</option>
					</select>
				</div>
				<div id="searchGrid"></div>
			</section>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
	filtrarBusca(-1);
</script>
    
<?php if ($tipoBusca == "marca") { ?>
	<section class="vitrine-sobre">
		<div>
			<h4>Sobre <?= $detalheMarca['Descricao'] ?></h4>
			<p><?= $detalheMarca['DescricaoDetalhada'] ?></p>
		</div>
	</section>
<?php } ?>
<?php } ?>
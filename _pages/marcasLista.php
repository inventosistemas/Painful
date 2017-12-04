<?php


$listaMarcas = getRest($endPoint['marcas']);
?>


<section class="content-breadcrumb">
	<div class="container">
		<div class="box-breadcrumb">
			<a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
			<span class="page-title">Marcas</span>
		</div>
	</div>
</section>

<section class="marcas-full cf">
    <h4>&nbsp;&nbsp;Marcas</h4>
    <div class="brands-logos-wrap">
        <ul>
            <?php
            $listaAlfabetica = [];
            
            function odernacaoMarcas($a, $b)
            {
                return strcmp($a['Descricao'], $b['Descricao']);
            }            
            
            if (!empty($listaMarcas))
            {
                usort($listaMarcas, "odernacaoMarcas"); // ordenas as marcas
            }
            
            foreach ((array) $listaMarcas as $marca)
            {
                $inicioCat =  strtoupper(substr($marca['Descricao'], 0, 1)); // maiscula da primeira letra do nome da categoria
                
                if (!array_key_exists($inicioCat, $listaAlfabetica)) 
                {
                    $listaAlfabetica[$inicioCat] = []; // se nao existir indice com a letra, cria-o.
                }
                
                $infoMarca = ["ID" => $marca['ID'],
                              "Descricao" => $marca['Descricao']
                    ];
                
                array_push($listaAlfabetica[$inicioCat], $infoMarca); //adiciona ao array de lista de marcas
                        
                echo "<li><a href=\"/marca?id=" . $marca['ID'] . "\"><img src=\"" . $marca['Logo'] . "\" /></a></li>";
            }
            
            if (!empty($listaAlfabetica))
            {
                ksort($listaAlfabetica); // ordena o array
            }
            ?>
        </ul>
    </div>
</section>

<div class="make-space-bet"></div>

<section class="conteudo">
    <?php
    for ($i = 0; $i < count($listaAlfabetica); $i++)
    {
    ?>
    <div id="accordion<?= $i + 1 ?>" class="mapa-alfabeto panel-group hidden-md-down" role="tablist" aria-multiselectable="true">
        <a href="javascript:void(0);" data-parent="#accordion<?= $i + 1 ?>" type="button" data-toggle="collapse" data-target="#collapse-<?= array_keys($listaAlfabetica)[$i] ?>" aria-expanded="false" aria-controls="collapse-<?= array_keys($listaAlfabetica)[$i] ?>">
            <h4><?= array_keys($listaAlfabetica)[$i] ?></h4>
            <span class="line-div"></span>
        </a>
        <div class="brand-list-wrap panel"> 
            <ul id="collapse-<?= array_keys($listaAlfabetica)[$i] ?>" class="brands-list collapse">
                <ul class="brand-list-col">
                    <?php
                    foreach ((array) $listaAlfabetica[array_keys($listaAlfabetica)[$i]] as $nomeMarca)
                    {
                        echo "<li><a href=\"/marca?id=" . $nomeMarca['ID'] . "\">" . $nomeMarca['Descricao'] . "</a></li>";
                    }
                    ?>
                </ul>
            </ul>
        </div>
    </div>
    <?php
    }
    ?>
</section>

<div class="make-space-bet"></div>

<!--
<section class="customize clearfix">
    <div><a href="">Customize sua prancha</a></div>
</section>
-->

<div class="make-space-bet"></div>

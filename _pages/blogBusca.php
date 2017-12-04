<?php

$phpGet = filter_input_array(INPUT_GET);
if (!empty($phpGet))
{
    $termoBusca = $phpGet[array_keys($phpGet)[0]];
}

$dadosBlog = getRest($endPoint['blog']);

$termoBuscaURL = trim(str_replace(' ', '%20', $termoBusca));
$buscaBlog = getRest(str_replace(['{IDBlog}','{termoBusca}'], [$dadosBlog['ID'], $termoBuscaURL], $endPoint['blogbusca']));
?>

<div class="blog-wrap clearfix">

    <section class="blog">
        <div class="blog-splash" style="background-image: url('<?= $dadosBlog['Imagem'] ?>');">
            <div class="clearfix">
                <p><?= $dadosBlog['Mensagem'] ?>
            </div>
        </div>

        <!-- inicio container -->
        <div class="col-md-8">
            <section class="blog-busca">
                <h4>Veja abaixo o que encontramos para você!<br>
                    Se mesmo assim não achou o que procura, tente uma palavra chave mais forte.</h4>
                <span class="post-qtd"><?= count($buscaBlog) ?> resultado<?= (count($buscaBlog) > 1 ? "s" : "")  ?> para "<strong><?= $termoBusca ?></strong>"</span>
                <ul>
                    <?php
                    foreach ((array) $buscaBlog as $busca)
                    {
                    ?>
                        <li>
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="<?= $busca['Imagem'] ?>" title="<?= $busca['Titulo'] ?>"/>
                                </div>
                                <div class="col-sm-9">							
                                    <p class="blog-posts-autor">(<?= date_format(date_create($busca['Data']), "d.m.Y") ?>)</p>
                                    <span><a href="/blogpost?id=<?= $busca['ID'] ?>"><?= $busca['Titulo'] ?></a></span>
                                    <p>Por: <?= $busca['Autor'] ?></p>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </section>
        </div>

        <div class="col-md-4">
            <section class="blog-sidebar">
                <form name="blogbusca" method="get" action="/blogbusca">
                    <div class="form-group input-icone">
                        <input class="form-control" placeholder="Procura por algo?" type="text" name="termobusca" required="required">
                        <button type="submit" class="btn glyphicon glyphicon-search"></button>

                    </div>
                </form>
				
					<div>
                    <ul>
                        <li><h5>Categorias</h5></li>
                        <?php
                            $categoriasBlog = getRest($endPoint['blogcategorias']);
                            foreach ((array) $categoriasBlog  as $categoria)
                            {
                                echo "<li><a href=\"/blogcategoria?id=" . $categoria['ID'] .  "\">" . $categoria['Descricao'] . "</a></li>";
                            }
                        ?>
                    </ul>
                    <h5>Postagens recentes</h5>
                    <ul>
                        <?php
                        $contPost = 1;
                        
                        $postsRecentes = getRest(str_replace(['{IDBlog}','{count}'], [$dadosBlog['ID'],'10'], $endPoint['blogrecentes']));
                        foreach ((array) $postsRecentes as $recente)
                        {
                            if ($contPost > 5) break; // limita a 5 posts na lateral
                            
                            $dataPost = date_create($recente['Data']);
                        ?>
                            <li>
                                <span>(<?= date_format($dataPost,"d.m.Y") ?>)</span>
                                <a href="/blogpost?id=<?= $recente['ID'] ?>"><?= $recente['Titulo'] ?></a>
                            </li>
                        <?php
                            $dataPost ++;
                        }
                        ?>
                    </ul>
                </div>

            </section>
        </div>
    </section>

    <div class="make-space-bet clearfix"></div>

</div>

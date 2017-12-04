<?php


$dadosBlog = getRest($endPoint['blog']);

$artigosDestaque = getRest(str_replace(['{IDBlog}','{count}'], [$dadosBlog['ID'],'5'], $endPoint['blogdestaques']));
?>

<div class="blog-wrap clearfix">
    <section class="blog">
        
        <div class="blog-splash" style="background-image: url('<?= $dadosBlog['Imagem'] ?>');">
            <div class="clearfix">
                <p><?= $dadosBlog['Mensagem'] ?></p>
            </div>
        </div>

        <!-- inicio container -->
        <div class="col-md-8">
            <section class="blog-posts">
                <div class="row">
                    <?php
                    if (!empty($artigosDestaque))
                    {
                    ?>
                        <div class="col-md-12">
                            <a href="/blogpost?id=<?= $artigosDestaque['Artigos']['0']['ID'] ?>">
                                <img src="<?= $artigosDestaque['Artigos']['0']['Imagem'] ?>" title="<?= $artigosDestaque['Artigos']['0']['Titulo'] ?>"/>
                            </a>
                            <span>(<?= date_format(date_create($artigosDestaque['Artigos']['0']['Data']), "d.m.Y") ?>)</span>
                            <a href="/blogpost?id=<?= $artigosDestaque['Artigos']['0']['ID'] ?>">
                                <?= $artigosDestaque['Artigos']['0']['Titulo'] ?>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <?php
                    $contArt = 0;
                    
                    foreach ((array) $artigosDestaque['Artigos'] as $artigo) 
                    {
                        $contArt ++;
                        if ($contArt == 1) continue; // pula o primeiro artigo do blog, ja exibido acima.
                        
                        if (in_array($contArt, [4])) // novo row na terceira linha
                        {
                            echo "</div><div class=\"row\">";
                        }
                    ?>
                        <div class="col-md-6">
                            <a href="/blogpost?id=<?= $artigo['ID'] ?>">
                                <img src="<?= $artigo['Imagem'] ?>" title="<?= $artigo['Titulo'] ?>"/>
                            </a>
                            <span>(<?= date_format(date_create($artigo['Data']), "d.m.Y") ?>)</span>
                            <a href="/blogpost?id=<?= $artigo['ID'] ?>">
                                <?= $artigo['Titulo'] ?>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            
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
                    <ul class="">
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
                            $contPost ++;
                        }
                        ?>
                    </ul>
                </div>
            </section>
        </div>
    </section>

    <div class="make-space-bet clearfix"></div>

</div>

<?php


$dadosCategoria = (!empty($artigosCategoria)) ? $artigosCategoria[0]['Categoria'] : ["ID" => -1, "Descricao" => ""];
?>

<div class="blog-wrap clearfix">

    <section class="blog">
        <div class="blog-splash" style="background-image: url('<?= $dadosBlog['Imagem'] ?>');">
            <div class="clearfix">
                <p><?= $dadosBlog['Mensagem'] ?><br>
                <small><?= $dadosCategoria['Descricao'] ?></small></p>
            </div>
        </div>

        <!-- inicio container -->

        <div class="col-md-8">
            <section class="blog-posts">
                <ol class="breadcrumb">
                    <li><a href="/blog"><?= $dadosBlog['Mensagem'] ?></a></li>
                    <li class="active"><?= $dadosCategoria['Descricao'] ?></li>
                </ol>

                <div class="row">
                    <?php
                    $contArt = 1;
                    foreach ((array) $artigosCategoria as $artigo) 
                    {
                        if ($contArt > 6) break; // exibe apenas o 6 primeiros artigos.
                    ?>
                        <div class="col-md-6">
                            <img src="<?= $artigo['Imagem'] ?>" title="<?= $artigo['Titulo'] ?>"/>
                            <span>(<?= date_format(date_create($artigo['Data']), "d.m.Y") ?>)</span>
                            <a href="/blogpost?id=<?= $artigo['ID'] ?>"><?= $artigo['Titulo'] ?></a>
                        </div>
                    <?php
                        if (($contArt > 1) && ($contArt <> count($artigosCategoria)) && (($contArt % 2) == 0)) // novo row a cada duas linhas
                        {
                            echo "</div><div class=\"row\">";
                        }                        
                        $contArt ++;
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
                    <?php
                    if (count($artigosCategoria) > 6) // se houver mais de 6 artigos, serão exidos na lateral
                    {
                    ?>
                        <h5>+ <?= $dadosCategoria['Descricao'] ?></h5>
                        <ul>
                            <?php
                            $contOut = 1;
                            foreach ((array) $artigosCategoria as $artigo) 
                            {                        
                                if ($contOut <= 6) continue; // lista do sexto artigo em diante. Primeiros 6 ja exibidos acima
                                
                                if ($contOut > 11) break; // limita a 5 posts na lateral
                            ?>
                                <li>
                                    <span>(<?= date_format(date_create($artigo['Data']), "d.m.Y") ?>)</span>				
                                    <a href="/blogpost?id=<?= $artigo['ID'] ?>"><?= $artigo['Titulo'] ?></a>
                                </li>
                            <?php
                                $contOut ++;
                            }
                            ?>
                        </ul>
                    <?php
                    }
                    ?>
                    <ul>
                        <li><h5>Outras categorias</h5></li>
                        <?php
                            $categoriasBlog = getRest($endPoint['blogcategorias']);
                            foreach ((array) $categoriasBlog  as $categoria)
                            {
                                if ($categoria['ID'] != $dadosCategoria['ID']) // Listas as demais categorias
                                {
                                    echo "<li><a href=\"/blogcategoria?id=" . $categoria['ID'] .  "\">" . $categoria['Descricao'] . "</a></li>";
                                }
                            }
                        ?>
                    </ul>
                </div>
            </section>
        </div>


    </section>



    <div class="make-space-bet clearfix"></div>

</div>

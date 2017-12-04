<?php

$textoBlog = str_ireplace('<IFRAME', '<div class="video-container"><IFRAME', $detalheArtigo['Texto']);
$textoBlog = str_ireplace('</IFRAME>', '</IFRAME></div>', $textoBlog);
?>

<div class="blog-wrap clearfix">

    <section class="blog">
        <div class="blog-splash" style="background-image: url('<?= $dadosBlog['Imagem'] ?>');">
            <div class="clearfix">
                <p><?= $dadosBlog['Mensagem'] ?><br>
                 <small><?= $detalheArtigo['Titulo'] ?></small></p>
            </div>
        </div>

        <!-- inicio container -->
        <div class="col-md-8">
            <section class="blog-posts">
                <ol class="breadcrumb">
                    <li><a href="/blog"><?= $dadosBlog['Mensagem'] ?></a></li>
                    <li><a href="/blogcategoria?id=<?= $detalheArtigo['Categoria']['ID'] ?>"><?= $detalheArtigo['Categoria']['Descricao'] ?> </a></li>
                    <li class="active"><?= $detalheArtigo['Titulo'] ?></li>
                </ol>
                <p><img src="<?= $detalheArtigo['Imagem'] ?>" title="<?= $detalheArtigo['Titulo'] ?>"/></p>
                <p class="blog-posts-autor">(<?= date_format(date_create($detalheArtigo['Data']), "d.m.Y") ?>) - <em>Por <?= $detalheArtigo['Autor'] ?></em></p>
                <p><?= $textoBlog ?></p>
                
                <p class="blog-posts-medias">
                    <a href="javascript: void(0);" onclick="window.open('https://twitter.com/intent/tweet?text=Gostei+de+um+post+no+blog+da+Hooray&url=<?= urlencode(URLSite . ltrim($URISite,"/")) ?>&hashtags=hooraybrasil','twitter', 'toolbar=0, status=0, width=650, height=450');"><img src="/images/site/hooray_twitter.png" border="0"></a>
                    &nbsp;&nbsp;
                    <a href="javascript: void(0);" onclick="window.open('https://www.facebook.com/sharer.php?u=<?= urlencode(URLSite . ltrim($URISite,"/")) ?>','facebook', 'toolbar=0, status=0, width=650, height=450');"><img src="/images/site/hooray_facebook.png" border="0"></a>
                <p>
                
                <div class="blog-posts-navegacao clearfix">
                    <?= ($detalheArtigo['IDArtigoAnterior'] > 0) ? "<div class=\"pull-left\"><a href=\"/blogpost?id=" . $detalheArtigo['IDArtigoAnterior'] . "\"><i class=\"glyphicon glyphicon-triangle-left\"></i> Artigo anterior</a></div>" : "" ?>
                    <?= ($detalheArtigo['IDArtigoPosterior'] > 0) ? "<div class=\"pull-right\"><a href=\"/blogpost?id=" . $detalheArtigo['IDArtigoPosterior'] . "\">Próximo artigo<i class=\"glyphicon glyphicon-triangle-right\"></i></a></div>" : "" ?>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <section class="blog-sidebar">
                <form name="blogbusca" method="get" action="/blogbusca">
                    <div class="form-group input-icone">
                        <input class="form-control" placeholder="Procura por algo?" type="text" name="termobusca" required="required">
                        <i class="glyphicon glyphicon-search"></i>
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
                        $postsRecentes = getRest(str_replace(['{IDBlog}','{count}'], [$dadosBlog['ID'],'10'], $endPoint['blogrecentes']));
                        foreach ((array) $postsRecentes as $recente)
                        {
                            $dataPost = date_create($recente['Data']);
                        ?>
                            <li>
                                <span>(<?= date_format($dataPost,"d.m.Y") ?>)</span>
                                <a href="/blogpost?id=<?= $recente['ID'] ?>"><?= $recente['Titulo'] ?></a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </section>
        </div>



        <div class="make-space-bet clearfix"></div>

</div>

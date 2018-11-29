<?php

$textoBlog = str_ireplace('<IFRAME', '<div class="video-container"><IFRAME', $detalheArtigo['Texto']);
$textoBlog = str_ireplace('</IFRAME>', '</IFRAME></div>', $textoBlog);
?>

<div class="blog-wrap clearfix">

    <section class="blog">

        <!-- inicio container -->
        <div class="col-md-12">
            <section class="blog-posts">
            
                <p><?= $textoBlog ?></p>
                
            </section>
        </div>
        <div class="make-space-bet clearfix"></div>
</div>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
    <head>
        <?php echo $html->charset(); ?>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?php
            //echo $html->meta('icon');
            echo $html->css( array( 'all.reset' ), 'stylesheet', array( 'media' => 'all' ) );
            echo $html->css( array( 'all.base' ), 'stylesheet', array( 'media' => 'all' ) );
            echo $html->css( array( 'screen.generic' ), 'stylesheet', array( 'media' => 'screen,presentation' ) );
            echo $html->css( array( 'print.generic' ), 'stylesheet', array( 'media' => 'print' ) );
            echo $scripts_for_layout;
        ?>
        <?php
            echo $javascript->link( 'prototype.js' );
            echo $javascript->link( 'tooltip.prototype.js' );
            echo $javascript->link( 'webrsa.common.prototype.js' );
        ?>
        <!-- TODO: à la manière de cake, dans les vues qui en ont besoin -->
        <script type="text/javascript">
            // prototype
            document.observe("dom:loaded", function() {
                var baseUrl = '<?php echo Router::url( '/', true );?>';
                make_treemenus( baseUrl );
//                 make_table_tooltips();
                make_folded_forms();
                mkTooltipTables();
            });
        </script>
        <!--[if IE]>
            <style type="text/css" media="screen, presentation">
                .treemenu { position: relative; }
                .treemenu, .treemenu *, #pageMenu, #pageWrapper { zoom: 1; }
            </style>
        <![endif]-->
    </head>
    <?php if( $this->base.'/' == $this->here ): ?>
        <body class="home">
    <?php else: ?>
        <body>
    <?php endif; ?>
        <div id="pageWrapper">
            <?php echo $this->element( 'header' );?>
            <?php echo $this->element( 'menu' );?>
            <div id="pageContent">
                <?php $session->flash();?>
                <?php echo $content_for_layout;?>
            </div>
            <?php echo $this->element( 'footer' );?>
<?php /*debug( $session->read( 'Auth' ) );*/?>
        </div>
    </body>
</html>
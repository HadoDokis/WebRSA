<h1><?php echo $this->pageTitle = 'Erreur:  Données des structures référentes incomplètes';?></h1>
<p>Veuillez contacter votre administrateur réseau afin qu'il complète les données suivantes:</p>

<ul>
    <?php if( array_search( true, $this->viewVars['params']['missing']['structurereferente'] ) !== false ):?>
        <?php foreach( $this->viewVars['params']['missing']['structurereferente'] as $key => $required ):?>
            <?php if( $required ):?>
                <li><?php echo $key;?></li>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
</ul>

<?php
    if( !empty( $structures ) ) {
        echo $html->tag( 'h2', 'Structures référentes à compléter' );
        $lis = array();
        foreach( $structures as $structure ) {
            $lis[] = $html->tag( 'li', $structure );
        }
        echo $html->tag( 'ul', implode( null, $lis ) );
    }
?>

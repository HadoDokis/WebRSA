<h1><?php echo $this->pageTitle = 'Erreur:  Données de l\'utilisateur incomplètes';?></h1>
<p>Veuillez contacter votre administrateur réseau afin qu'il complète les données suivantes:</p>

<ul>
    <?php if( array_search( true, $this->viewVars['params']['missing']['user'] ) !== false ):?>
        <li><strong>Utilisateur</strong>
            <ul>
                <?php foreach( $this->viewVars['params']['missing']['user'] as $key => $required ):?>
                    <?php if( $required ):?>
                        <li><?php echo $key;?></li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </li>
    <?php endif;?>

    <?php if( array_search( true, $this->viewVars['params']['missing']['serviceinstructeur'] ) !== false ):?>
        <li><strong>Service instructeur</strong>
            <ul>
                <?php foreach( $this->viewVars['params']['missing']['serviceinstructeur'] as $key => $required ):?>
                    <?php if( $required ):?>
                        <li><?php echo $key;?></li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </li>
    <?php endif;?>
</ul>

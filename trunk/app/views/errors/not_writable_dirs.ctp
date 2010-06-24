<h1><?php echo $this->pageTitle = 'Erreur: Répertoires en lecture seule sur le système';?></h1>
<p>Veuillez contacter votre administrateur réseau afin qu'il s'assure que les répertoires suivants existent et qu'il soit possible d'y écrire:</p>

<ul>
    <?php if( array_search( true, $this->viewVars['params']['directories'] ) !== false ):?>
		<?php foreach( $this->viewVars['params']['directories'] as $key => $binary ):?>
			<?php if( $binary ):?>
				<li><?php echo $binary;?></li>
			<?php endif;?>
		<?php endforeach;?>
    <?php endif;?>
</ul>

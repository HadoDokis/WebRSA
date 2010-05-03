<h1><?php echo $this->pageTitle = 'Erreur: Fichier webrsa.inc incomplet';?></h1>
<p>Veuillez contacter votre administrateur réseau afin qu'il complète les données suivantes:</p>

<ul>
    <?php if( array_search( true, $this->viewVars['params']['paths'] ) !== false ):?>
		<?php foreach( $this->viewVars['params']['paths'] as $key => $path ):?>
			<?php if( $path ):?>
				<li><?php echo $path;?></li>
			<?php endif;?>
		<?php endforeach;?>
    <?php endif;?>
</ul>

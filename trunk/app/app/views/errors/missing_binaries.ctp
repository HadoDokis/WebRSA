<h1><?php echo $this->pageTitle = 'Erreur: Exécutables non présents sur le système';?></h1>
<p>Veuillez contacter votre administrateur réseau afin qu'il installe les programmes suivants:</p>

<ul>
    <?php if( array_search( true, $this->viewVars['params']['binaries'] ) !== false ):?>
		<?php foreach( $this->viewVars['params']['binaries'] as $key => $binary ):?>
			<?php if( $binary ):?>
				<li><?php echo $binary;?></li>
			<?php endif;?>
		<?php endforeach;?>
    <?php endif;?>
</ul>

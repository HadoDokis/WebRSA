<h1><?php echo $this->pageTitle = 'Erreur:  Données de l\'APRE';?></h1>

<p>Veuillez contacter votre administrateur réseau afin qu'il complète les données suivantes dans <code>app/config/webrsa.inc</code>:</p>

<ul>
	<?php
		if( empty( $this->viewVars['params']['montantMaxComplementaires'] ) ) {
			echo $html->tag( 'li', 'Apre.montantMaxComplementaires' );
		}
		if( empty( $this->viewVars['params']['periodeMontantMaxComplementaires'] ) ) {
			echo $html->tag( 'li', 'Apre.periodeMontantMaxComplementaires' );
		}
	?>
</ul>

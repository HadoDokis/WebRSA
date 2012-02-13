<?php
	echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )."</h3>";

	if( in_array( 'dossierseps::choose', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->affecteLink(
			'Affecter les dossiers',
			array( 'controller' => 'dossierseps', 'action' => 'choose', Set::classicExtract( $commissionep, 'Commissionep.id' ), "#{$theme}" )
		).' </li></ul>';
	}
	else {
		echo '<li><span class="disabled"> Affecter les dossiers </span></li>';
	}

	if( empty( $dossiers[$theme] ) ) {
		echo '<p class="notice">Il n\'existe aucun dossier de cette thématique associé à cette commission d\'EP.</p>';
	}
	else {
// 	debug( $dossiers[$theme] );
		echo $default2->index(
			$dossiers[$theme],
			array(
				'Dossier.numdemrsa' => array( 'type' => 'text' ),
				'Dossier.matricule' => array( 'type' => 'text' ),
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.locaadr',
				'Nonrespectsanctionep93.origine',
				'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			),
			array(
				'actions' => array(
					'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'historiqueseps', 'action' => 'index', '#Personne.id#' ), 'class' => 'external' ),
					'Commissionseps::printConvocationBeneficiaire' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'printConvocationBeneficiaire', '#Passagecommissionep.id#' ), 'disabled' => empty( $disableConvocationBeneficiaire ))
				),
				'options' => $options,
				'id' => $theme,
				'trClass' => $trClass,
			)
		);
	}
	echo "</div>";
?>
<?php
	echo '<table><thead><tr>';
	
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nir', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
	echo $xhtml->tag( 'th', __d( 'adresse', 'Adresse.locaadr', true ) );
	echo $xhtml->tag( 'th', __d( 'referent', 'Referent.nom_complet', true ) );
// 	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.datedemande', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.decisioncov', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.datevalidation', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.commentaire', true ) );
	echo $xhtml->tag( 'th', 'Actions' );
		
		
// 	$datecommission = preg_replace( '/^([^ ]+) .*$/', '\1', $cov58['Cov58']['datecommission'] );

echo '</tr></thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossiercov ) {
// debug( $d );
		echo $form->input( "{$theme}.{$i}.id", array( 'type' => 'hidden', 'value' => $dossiercov[$theme]['id'] ) );
		echo $xhtml->tableCells(
			array(
				$dossiercov['Personne']['nir'],
				implode( ' ', array( $dossiercov['Personne']['qual'], $dossiercov['Personne']['nom'], $dossiercov['Personne']['prenom'] ) ),
				implode( ' ', array( $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'], $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['codepos'], $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['locaadr'] ) ),
				implode( ' ', array( $dossiercov['Referent']['qual'], $dossiercov['Referent']['nom'], $dossiercov['Referent']['prenom'] ) ),
				$form->input( "{$theme}.{$i}.decisioncov", array( 'type' => 'select', 'options' => $decisionscovs, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.datevalidation", array( 'type' => 'date', 'selected' => $cov58['Cov58']['datecommission'], 'dateFormat' => 'DMY', 'label' => false, 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 ) ),
				$form->input( "{$theme}.{$i}.commentaire", array( 'type' => 'textarea', 'label' => false ) ),
				$xhtml->viewLink( 'Voir', array( 'controller' => 'contratsinsertion', 'action' => 'index', $dossiercov['Personne']['id'] ), true, true )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			observeDisableFieldsOnValue(
				'Propocontratinsertioncov58<?php echo $i;?>Decisioncov',
				[ 'Propocontratinsertioncov58<?php echo $i;?>DatevalidationDay', 'Propocontratinsertioncov58<?php echo $i;?>DatevalidationMonth', 'Propocontratinsertioncov58<?php echo $i;?>DatevalidationYear' ],
				'accepte',
				false
			);
		<?php endfor;?>
	});
</script>
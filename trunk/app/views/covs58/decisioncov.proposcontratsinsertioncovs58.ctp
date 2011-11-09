<?php
	echo '<table><thead><tr>';
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nir', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
	echo $xhtml->tag( 'th', __d( 'adresse', 'Adresse.locaadr', true ) );
	echo $xhtml->tag( 'th', __d( 'referent', 'Referent.nom_complet', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.decisioncov', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.datevalidation', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.commentaire', true ) );
	echo $xhtml->tag( 'th', 'Actions' );




	echo '</tr></thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossiercov ) {
// debug($dossiercov);


	$hiddenFields = $form->input( "Decisionpropocontratinsertioncov58.{$i}.id", array( 'type' => 'hidden' ) ).
					$form->input( "Decisionpropocontratinsertioncov58.{$i}.etapecov", array( 'type' => 'hidden', 'value' => 'finalise' ) ).
					$form->input( "Decisionpropocontratinsertioncov58.{$i}.passagecov58_id", array( 'type' => 'hidden', 'value' => $dossiercov['Passagecov58'][0]['id'] ) );

		echo $form->input( "{$theme}.{$i}.id", array( 'type' => 'hidden', 'value' => $dossiercov[$theme]['id'] ) );
		echo $xhtml->tableCells(
			array(
				$dossiercov['Personne']['nir'],
				implode( ' ', array( $dossiercov['Personne']['qual'], $dossiercov['Personne']['nom'], $dossiercov['Personne']['prenom'] ) ),
				implode( ' ', array( $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'], $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['codepos'], $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['locaadr'] ) ),
				implode( ' ', array( $dossiercov['Propocontratinsertioncov58']['Referent']['qual'], $dossiercov['Propocontratinsertioncov58']['Referent']['nom'], $dossiercov['Propocontratinsertioncov58']['Referent']['prenom'] ) ),



				array(
					$form->input( "Decisionpropocontratinsertioncov58.{$i}.decisioncov", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionpropocontratinsertioncov58']['decisioncov'] ) ),
					array( 'id' => "Decisionpropocontratinsertioncov58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionpropocontratinsertioncov58'][$i]['decisioncov'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionpropocontratinsertioncov58.{$i}.datevalidation", array( 'type' => 'date', 'selected' => $cov58['Cov58']['datecommission'], 'dateFormat' => 'DMY', 'label' => false, 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 ) ),
					( !empty( $this->validationErrors['Decisionpropocontratinsertioncov58'][$i]['datevalidation'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionpropocontratinsertioncov58.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
				$hiddenFields,
				$xhtml->viewLink( 'Voir', array( 'controller' => 'contratsinsertion', 'action' => 'index', $dossiercov['Personne']['id'] ), true, true ),
			)
		);
	}
	echo '</tbody></table>';
?>


<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			observeDisableFieldsOnValue(
				'Decisionpropocontratinsertioncov58<?php echo $i;?>Decisioncov',
				[ 'Decisionpropocontratinsertioncov58<?php echo $i;?>DatevalidationDay', 'Decisionpropocontratinsertioncov58<?php echo $i;?>DatevalidationMonth', 'Decisionpropocontratinsertioncov58<?php echo $i;?>DatevalidationYear' ],
				'valide',
				false
			);
		<?php endfor;?>
	});
</script>
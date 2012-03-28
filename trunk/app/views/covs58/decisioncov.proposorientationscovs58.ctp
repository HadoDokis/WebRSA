<?php
// debug($this->data);
	echo '<table><thead><tr>';

	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nir', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
	echo $xhtml->tag( 'th', __d( 'adresse', 'Adresse.locaadr', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.datedemande', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Propoorientationcov58.referentorientant_id', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.proporeferent', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.decisioncov', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.choixcov', true ), array( 'colspan' => 3 ) );


	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.commentaire', true ) );
	echo $xhtml->tag( 'th', 'Actions' );




	echo '</tr></thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossiercov ) {

// debug($dossiers[$theme]['liste']);

	$hiddenFields = $form->input( "Decisionpropoorientationcov58.{$i}.id", array( 'type' => 'hidden' ) ).
					$form->input( "Decisionpropoorientationcov58.{$i}.etapecov", array( 'type' => 'hidden', 'value' => 'finalise' ) ).
					$form->input( "Decisionpropoorientationcov58.{$i}.passagecov58_id", array( 'type' => 'hidden', 'value' => $dossiercov['Passagecov58'][0]['id'] ) );

		echo $form->input( "{$theme}.{$i}.id", array( 'type' => 'hidden', 'value' => $dossiercov[$theme]['id'] ) );
		echo $xhtml->tableCells(
			array(
				$dossiercov['Personne']['nir'],
				implode( ' ', array( $dossiercov['Personne']['qual'], $dossiercov['Personne']['nom'], $dossiercov['Personne']['prenom'] ) ),
				implode( ' ', array( $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossiercov[$theme]['datedemande'] ),
				Set::enum( Set::classicExtract( $dossiercov, 'Propoorientationcov58.referentorientant_id' ), $referentsorientants ),
				implode( ' - ', Set::filter( array( $dossiercov['Propoorientationcov58']['Typeorient']['lib_type_orient'], $dossiercov['Propoorientationcov58']['Structurereferente']['lib_struc'], implode( ' ', Set::filter( array( $dossiercov['Propoorientationcov58']['Referent']['qual'], $dossiercov['Propoorientationcov58']['Referent']['nom'], $dossiercov['Propoorientationcov58']['Referent']['prenom'] ) ) ) ) ) ),

				array(
					$form->input( "Decisionpropoorientationcov58.{$i}.decisioncov", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionpropoorientationcov58']['decisioncov'] ) ),
					array( 'id' => "Decisionpropoorientationcov58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionpropoorientationcov58'][$i]['decisioncov'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionpropoorientationcov58.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $typesorients ) ),
					( !empty( $this->validationErrors['Decisionpropoorientationcov58'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionpropoorientationcov58.{$i}.structurereferente_id", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $structuresreferentes ) ),
					( !empty( $this->validationErrors['Decisionpropoorientationcov58'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionpropoorientationcov58.{$i}.referent_id", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $referents ) ),
					( !empty( $this->validationErrors['Decisionpropoorientationcov58'][$i]['referent_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionpropoorientationcov58.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
				$hiddenFields,
				$xhtml->viewLink( 'Voir', array( 'controller' => 'orientsstructs', 'action' => 'index', $dossiercov['Personne']['id'] ), true, true ),
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionpropoorientationcov58<?php echo $i?>StructurereferenteId', 'Decisionpropoorientationcov58<?php echo $i?>TypeorientId' );
			try { $( 'Decisionpropoorientationcov58<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			dependantSelect( 'Decisionpropoorientationcov58<?php echo $i?>ReferentId', 'Decisionpropoorientationcov58<?php echo $i?>StructurereferenteId' );
			try { $( 'Decisionpropoorientationcov58<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionpropoorientationcov58<?php echo $i;?>Decisioncov',
				[ 'Decisionpropoorientationcov58<?php echo $i;?>TypeorientId', 'Decisionpropoorientationcov58<?php echo $i;?>StructurereferenteId', 'Decisionpropoorientationcov58<?php echo $i;?>ReferentId' ],
				'refuse',
				false
			);
		<?php endfor;?>
	});
</script>
<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th>Avis EPL</th>
<th colspan="3">Décision CG</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
// debug($dossierep);
		if ( $dossierep['Nonorientationpro93']['Decisionnonorientationpro93'][1]['decision'] == 'maintienref' ) {
			$avisep = $dossierep['Nonorientationpro93']['Orientstruct']['Typeorient']['lib_type_orient'].' - '.$dossierep['Nonorientationpro93']['Orientstruct']['Structurereferente']['lib_struc'];
		}
		else {
			$avisep = $dossierep['Nonorientationpro93']['Decisionnonorientationpro93'][1]['Typeorient']['lib_type_orient'].' - '.$dossierep['Nonorientationpro93']['Decisionnonorientationpro93'][1]['Structurereferente']['lib_struc'];
		}
		
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationpro93']['Orientstruct']['date_valid'] ),
				$dossierep['Nonorientationpro93']['Orientstruct']['Typeorient']['lib_type_orient'].' - '.
				$dossierep['Nonorientationpro93']['Orientstruct']['Structurereferente']['lib_struc'].' - '.
				implode( ' ', array( @$dossierep['Nonorientationpro93']['Orientstruct']['Referent']['qual'], @$dossierep['Nonorientationpro93']['Orientstruct']['Referent']['nom'], @$dossierep['Nonorientationpro93']['Orientstruct']['Referent']['prenom'] ) ),
				$avisep,

				$form->input( "Nonorientationpro93.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Nonorientationpro93']['id'] ) ).
				$form->input( "Nonorientationpro93.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisionnonorientationpro93.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data[$i]['id'] ) ).
				$form->input( "Decisionnonorientationpro93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Decisionnonorientationpro93.{$i}.defautinsertionep66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Nonorientationpro93']['id'] ) ).

				$form->input( "Decisionnonorientationpro93.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisionnonorientationpro93']['decision'], 'value' => @$this->data[$i]['decision'] ) ),
				$form->input( "Decisionnonorientationpro93.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true, 'value' => @$this->data[$i]['typeorient_id'] ) ),
				$form->input( "Decisionnonorientationpro93.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true, 'type' => 'select', 'value' => @$this->data[$i]['structurereferente_id'] ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
 		dependantSelect( 'Decisionnonorientationpro93<?php echo $i?>StructurereferenteId', 'Decisionnonorientationpro93<?php echo $i?>TypeorientId' );
 		try { $( 'Decisionnonorientationpro93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Decisionnonorientationpro93<?php echo $i;?>Decision',
			[
				'Decisionnonorientationpro93<?php echo $i;?>TypeorientId',
				'Decisionnonorientationpro93<?php echo $i;?>StructurereferenteId'
			],
			'reorientation',
			false
		);
		<?php endfor;?>
	});
</script>
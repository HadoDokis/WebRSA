<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th colspan="3">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {

		$hiddenFields = $form->input( "Nonorientationproep93.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Nonorientationproep93']['id'] ) ).
						$form->input( "Nonorientationproep93.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data[$i]['id'] ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep93']['Orientstruct']['date_valid'] ),
				implode( ' - ', Set::filter( array( $dossierep['Nonorientationproep93']['Orientstruct']['Typeorient']['lib_type_orient'], $dossierep['Nonorientationproep93']['Orientstruct']['Structurereferente']['lib_struc'], implode( ' ', array( @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['qual'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['nom'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['prenom'] ) ) ) ) ),

				array(
					$form->input( "Decisionnonorientationproep93.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisionnonorientationproep93']['decision'] ) ),
					array( 'id' => "Decisionnonorientationproep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionnonorientationproep93.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionnonorientationproep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
// 				array( $form->input( "Decisionnonorientationproep93.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => '2' ) ),
				$form->input( "Decisionnonrespectsanctionep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields
			),
			array( 'class' => 'odd' ),
			array( 'class' => 'even' )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionnonorientationproep93<?php echo $i?>StructurereferenteId', 'Decisionnonorientationproep93<?php echo $i?>TypeorientId' );
			try { $( 'Decisionnonorientationproep93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionnonorientationproep93<?php echo $i;?>Decision',
				[
					'Decisionnonorientationproep93<?php echo $i;?>TypeorientId',
					'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId'
				],
				'reorientation',
				false
			);

			$( 'Decisionnonorientationproep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanAnnuleReporte( 'Decisionnonorientationproep93<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanAnnuleReporte( 'Decisionnonorientationproep93<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ] );

// 			$( 'Decisionnonorientationproep93<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				afficheRaisonpassage( 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ], 'Decisionnonorientationproep93<?php echo $i;?>Raisonnonpassage' );
// 			});
// 			afficheRaisonpassage( 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ], 'Decisionnonorientationproep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
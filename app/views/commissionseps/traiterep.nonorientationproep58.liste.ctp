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
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$hiddenFields = $form->input( "Decisionnonorientationproep58.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep58']['Orientstruct']['date_valid'] ),
				implode(
					' - ',
					Set::filter( array(
						$dossierep['Nonorientationproep58']['Orientstruct']['Typeorient']['lib_type_orient'],
						$dossierep['Nonorientationproep58']['Orientstruct']['Structurereferente']['lib_struc'],
						implode(
							' ',
							Set::filter( array(
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['qual'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['nom'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['prenom']
							) )
						)
					) )
				),

				array(
					$form->input( "Decisionnonorientationproep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisionnonorientationproep58']['decision'] ) ),
					array( 'id' => "Decisionnonorientationproep58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionnonorientationproep58'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionnonorientationproep58.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep58'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionnonorientationproep58.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep58'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionnonorientationproep58.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields
			),
			array( 'class' => "odd {$multiple}" ),
			array( 'class' => "even {$multiple}" )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionnonorientationproep58<?php echo $i?>StructurereferenteId', 'Decisionnonorientationproep58<?php echo $i?>TypeorientId' );
			try { $( 'Decisionnonorientationproep58<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionnonorientationproep58<?php echo $i;?>Decision',
				[
					'Decisionnonorientationproep58<?php echo $i;?>TypeorientId',
					'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId'
				],
				'reorientation',
				false
			);

			$( 'Decisionnonorientationproep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionnonorientationproep58<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep58<?php echo $i;?>Decision', [ 'Decisionnonorientationproep58<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionnonorientationproep58<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep58<?php echo $i;?>Decision', [ 'Decisionnonorientationproep58<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId' ] );
		<?php endfor;?>
	});
</script>
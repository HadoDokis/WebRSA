<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th colspan="2">Orientation actuelle</th>
<th colspan="2">Proposition référent</th>
<th colspan="3">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {

		$hiddenFields = $form->input( "Decisionregressionorientationep58.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionregressionorientationep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionregressionorientationep58.{$i}.regressionorientationep58_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionregressionorientationep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				@$dossierep['Personne']['Orientstruct'][0]['Typeorient']['lib_type_orient'],
				@$dossierep['Personne']['Orientstruct'][0]['Structurereferente']['lib_struc'],
				@$dossierep['Regressionorientationep58']['Typeorient']['lib_type_orient'],
				@$dossierep['Regressionorientationep58']['Structurereferente']['lib_struc'],

				array( $form->input( "Decisionregressionorientationep58.{$i}.decision", array( 'label' => false, 'type' => 'select', 'options' => @$options['Decisionregressionorientationep58']['decision'], 'empty' => true ) ), array( 'id' => "Decisionregressionorientationep58{$i}DecisionColumn" ) ),
				$form->input( "Decisionregressionorientationep58.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
				$form->input( "Decisionregressionorientationep58.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
// 				array( $form->input( "Decisionregressionorientationep58.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => '2' ) ),
				$form->input( "Decisionregressionorientationep58.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
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
			dependantSelect( 'Decisionregressionorientationep58<?php echo $i?>StructurereferenteId', 'Decisionregressionorientationep58<?php echo $i?>TypeorientId' );
			try { $( 'Decisionregressionorientationep58<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionregressionorientationep58<?php echo $i;?>Decision',
				[ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ],
				'accepte',
				false
			);

			$( 'Decisionregressionorientationep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanAnnuleReporte( 'Decisionregressionorientationep58<?php echo $i;?>DecisionColumn', 3, 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanAnnuleReporte( 'Decisionregressionorientationep58<?php echo $i;?>DecisionColumn', 3, 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ] );

// 			$( 'Decisionregressionorientationep58<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				afficheRaisonpassage( 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
// 			});
// 			afficheRaisonpassage( 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
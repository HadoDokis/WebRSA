<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th colspan="2">Orientation actuelle</th>
<th colspan="2">Proposition référent</th>
<th colspan="3">Avis EPL</th>
</tr>
</thead><tbody>';
// debug($this->data);
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
// debug($dossierep);
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				@$dossierep['Personne']['Orientstruct'][0]['Typeorient']['lib_type_orient'],
				@$dossierep['Personne']['Orientstruct'][0]['Structurereferente']['lib_struc'],
				@$dossierep['Regressionorientationep58']['Typeorient']['lib_type_orient'],
				@$dossierep['Regressionorientationep58']['Structurereferente']['lib_struc'],

				$form->input( "Decisionregressionorientationep58.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionregressionorientationep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionregressionorientationep58.{$i}.regressionorientationep58_id", array( 'type' => 'hidden' ) ).
// 				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisionregressionorientationep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
// 				$form->input( "Decisionregressionorientationep58.{$i}.regressionorientationep58_id", array( 'type' => 'hidden', 'value' => @$dossierep['Regressionorientationep58']['id'] ) ).
				$form->input( "Decisionregressionorientationep58.{$i}.decision", array( 'label' => false, 'type' => 'select', 'options' => @$options['Decisionregressionorientationep58']['decision'], 'empty' => true ) ),
				$form->input( "Decisionregressionorientationep58.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
				$form->input( "Decisionregressionorientationep58.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
				array( $form->input( "Decisionregressionorientationep58.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea', 'empty' => true ) ), array( 'colspan' => '2' ) )
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $commissionep );
// 	debug( $options );
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
				afficheRaisonpassage( 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
			});
			afficheRaisonpassage( 'Decisionregressionorientationep58<?php echo $i;?>Decision', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
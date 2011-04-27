<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif de la demande</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Orientation préconisée</th>
<th>Structure référente préconisée</th>
<th colspan=\'3\'>Avis EP</th>
</tr>
</thead><tbody>';
// debug( $this->data );
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Reorientationep93']['Motifreorientep93']['name'],
				$dossierep['Reorientationep93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Reorientationep93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Reorientationep93']['Typeorient']['lib_type_orient'],
				@$dossierep['Reorientationep93']['Structurereferente']['lib_struc'],
// 				$form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden', 'value' => @$dossierep['Reorientationep93']['id'] ) ).
// 				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
// 				$form->input( "Reorientationep93.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Reorientationep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Reorientationep93']['id']*/ ) ).
				$form->input( "Reorientationep93.{$i}.dossierep_id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Dossierep']['id']*/ ) ).
				$form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => @$record['id']*/ ) ).
				$form->input( "Decisionreorientationep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).

				$form->input( "Decisionreorientationep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
// 				$form->input( "Decisionreorientationep93.{$i}.reorientationep93_id", array( 'type' => 'hidden'/*, 'value' => @$dossierep['Reorientationep93']['id']*/ ) ).
				$form->input( "Decisionreorientationep93.{$i}.decision", array( 'label' => false, 'type' => 'select', 'options' => @$options['Decisionreorientationep93']['decision'], 'empty' => true ) ),
				$form->input( "Decisionreorientationep93.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
				$form->input( "Decisionreorientationep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true ) ),
				array( $form->input( "Decisionreorientationep93.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea', 'empty' => true ) ), array( 'colspan' => '2' ) )
			)
		);
	}
	echo '</tbody></table>';
// debug( $dossiers );
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $commissionep );
// 	debug( $options );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId', 'Decisionreorientationep93<?php echo $i?>TypeorientId' );
			try { $( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionreorientationep93<?php echo $i;?>Decision',
				[ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ],
				'accepte',
				false
			);

			$( 'Decisionreorientationep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				afficheRaisonpassage( 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ], 'Decisionreorientationep93<?php echo $i;?>Raisonnonpassage' );
			});
			afficheRaisonpassage( 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ], 'Decisionreorientationep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
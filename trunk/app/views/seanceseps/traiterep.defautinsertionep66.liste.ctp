<!--<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
		dependantSelect( 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i?>TypeorientId' );
		try { $( 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		/*observeDisableFieldsOnValue(
			'Decisiondefautinsertionep66<?php echo $i;?>Decision',
			[ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId' ],
			'accepte',
			false
		);*/
		<?php endfor;?>
	});
</script>-->

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
<th colspan="2">Flux PE</th>
<!--<th colspan="2">Avis EPL</th>-->
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
				$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],
				$locale->date( __( 'Locale->date', true ), @$dossierep['Defautinsertionep66']['Historiqueetatpe']['date'] ),
				@$dossierep['Defautinsertionep66']['Historiqueetatpe']['motif']/*,
				$form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Decisiondefautinsertionep66'][$i]['id'] ) ).
// 				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Defautinsertionep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.defautinsertionep66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Defautinsertionep66']['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true ) ),
				$form->input( "Decisiondefautinsertionep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true, 'type' => 'select' ) )*/
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $seanceep );
// 	debug( $options );
debug( $dossiers[$theme]['liste'] );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
 		dependantSelect( 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i?>TypeorientId' );
 		try { $( 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Decisiondefautinsertionep66<?php echo $i;?>Decision',
			[
				'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId',
				'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId'
			],
			[
				'reorientationprofverssoc',
				'reorientationsocversprof'
			],
			false
		);
		<?php endfor;?>
	});
</script>

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
<!--<th colspan="2">Flux PE</th>-->
<th>Origine</th>
<th>Date de radiation</th>
<th>Motif de radiation</th>
<th colspan="3">Avis EPL</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		// Pré-remplissage avec les valeurs de l'avis EP -> FIXME prepareFormData
		if( empty( $this->data ) ) {
			if( @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1]['etape'] == 'cg' ) {
				$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1];
			}
			else {
				$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][0];
			}

			$decisionsdefautsinsertionseps66[$i]['decision'] = $record['decision'];
			$decisionsdefautsinsertionseps66[$i]['typeorient_id'] = $record['typeorient_id'];
			$decisionsdefautsinsertionseps66[$i]['structurereferente_id'] = implode( '_', array( $record['typeorient_id'], $record['structurereferente_id'] ) );
		}
		else {
			$decisionsdefautsinsertionseps66[$i]['decision'] = $this->data['Decisiondefautinsertionep66'][$i]['decision'];
			$decisionsdefautsinsertionseps66[$i]['typeorient_id'] = $this->data['Decisiondefautinsertionep66'][$i]['typeorient_id'];
			$decisionsdefautsinsertionseps66[$i]['structurereferente_id'] = $this->data['Decisiondefautinsertionep66'][$i]['structurereferente_id'];
		}

		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
				$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],
				Set::enum( $dossierep['Defautinsertionep66']['origine'], $options['Defautinsertionep66']['origine'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Defautinsertionep66']['Historiqueetatpe']['date'] ),
				@$dossierep['Defautinsertionep66']['Historiqueetatpe']['motif'],

				$form->input( "Defautinsertionep66.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Defautinsertionep66']['id'] ) ).
				$form->input( "Defautinsertionep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden', 'value' => @$record['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.defautinsertionep66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Defautinsertionep66']['id'] ) ).

				$form->input( "Decisiondefautinsertionep66.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisiondefautinsertionep66']['decision'], 'value' => @$decisionsdefautsinsertionseps66[$i]['decision'] ) ),
				$form->input( "Decisiondefautinsertionep66.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Decisiondefautinsertionep66']['typeorient_id'], 'empty' => true, 'value' => @$decisionsdefautsinsertionseps66[$i]['typeorient_id'] ) ),
				$form->input( "Decisiondefautinsertionep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Decisiondefautinsertionep66']['structurereferente_id'], 'empty' => true, 'type' => 'select', 'value' => @$decisionsdefautsinsertionseps66[$i]['structurereferente_id'] ) )
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $seanceep );
// debug( $dossiers[$theme]['liste'] );
// debug( $options );
?>

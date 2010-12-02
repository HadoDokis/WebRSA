<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
		dependantSelect( 'Nvsrepreorient66<?php echo $i?>StructurereferenteId', 'Nvsrepreorient66<?php echo $i?>TypeorientId' );
		try { $( 'Nvsrepreorient66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		/*observeDisableFieldsOnValue(
			'Nvsrepreorient66<?php echo $i;?>Decision',
			[ 'Nvsrepreorient66<?php echo $i;?>TypeorientId', 'Nvsrepreorient66<?php echo $i;?>StructurereferenteId' ],
			'accepte',
			false
		);*/
		<?php endfor;?>
	});
</script>

<?php
	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Qualité</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Orientation préconisée</th>
<th>Structure référente préconisée</th>
<th>Orientation choisie</th>
<th>Structure référente choisie</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				$dossierep['Personne']['qual'],
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Saisineepbilanparcours66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Saisineepbilanparcours66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Saisineepbilanparcours66']['Typeorient']['lib_type_orient'],
				@$dossierep['Saisineepbilanparcours66']['Structurereferente']['lib_struc'],
				$form->input( "Nvsrepreorient66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Nvsrepreorient66'][$i]['id'] ) ).
				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Nvsrepreorient66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Nvsrepreorient66.{$i}.saisineepbilanparcours66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisineepbilanparcours66']['id'] ) ).
				$form->input( "Nvsrepreorient66.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true ) ),
				$form->input( "Nvsrepreorient66.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true, 'type' => 'select' ) )
			)
		);
	}
	echo '</tbody></table>';
	echo $form->submit( 'Enregistrer' );
	echo $form->end();

// 	debug( $seanceep );
// 	debug( $options );
?>

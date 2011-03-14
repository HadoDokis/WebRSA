<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Type de réorientation</th>
<th>Proposition référent</th>
<th>Avis EPL</th>
<th>Avis CG</th>
<th colspan="3">Décision coordonnateur/CG</th>
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
				$dossierep['Saisineepbilanparcours66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Saisineepbilanparcours66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
				(!empty($dossierep['Saisineepbilanparcours66']['Bilanparcours66']['reorientation'])) ? __d('bilanparcours66', 'ENUM::REORIENTATION::'.$dossierep['Saisineepbilanparcours66']['Bilanparcours66']['reorientation'], true) : '',
				@$dossierep['Saisineepbilanparcours66']['Typeorient']['lib_type_orient'].' - '.
				@$dossierep['Saisineepbilanparcours66']['Structurereferente']['lib_struc'],
				@$dossierep['Saisineepbilanparcours66']['Nvsrepreorient66'][0]['Typeorient']['lib_type_orient'].' - '.
				@$dossierep['Saisineepbilanparcours66']['Nvsrepreorient66'][0]['Structurereferente']['lib_struc'],
				$form->input( "Nvsrepreorient66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Nvsrepreorient66'][$i]['id'] ) ).
// 				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Saisineepbilanparcours66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Nvsrepreorient66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Nvsrepreorient66.{$i}.saisineepbilanparcours66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisineepbilanparcours66']['id'] ) ).
				$form->input( "Nvsrepreorient66.{$i}.decision", array( 'label' => false, 'options' => @$options['Nvsrepreorient66']['decision'], 'empty' => true ) ),
				$form->input( "Nvsrepreorient66.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true ) ),
				$form->input( "Nvsrepreorient66.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true ) ),
				$form->input( "Nvsrepreorient66.{$i}.referent_id", array( 'label' => false, 'options' => @$options['Seanceep']['referent_id'], 'empty' => true ) ),
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $seanceep );
// 	debug( $options );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
		dependantSelect( 'Nvsrepreorient66<?php echo $i?>StructurereferenteId', 'Nvsrepreorient66<?php echo $i?>TypeorientId' );
		try { $( 'Nvsrepreorient66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }
		
		dependantSelect( 'Nvsrepreorient66<?php echo $i?>ReferentId', 'Nvsrepreorient66<?php echo $i?>StructurereferenteId' );
		try { $( 'Nvsrepreorient66<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Nvsrepreorient66<?php echo $i;?>Decision',
			[ 'Nvsrepreorient66<?php echo $i;?>TypeorientId', 'Nvsrepreorient66<?php echo $i;?>StructurereferenteId', 'Nvsrepreorient66<?php echo $i;?>ReferentId' ],
			'accepte',
			false
		);
		<?php endfor;?>
	});
</script>
<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Type de réorientation</th>
<th colspan="2">Proposition référent</th>
<th colspan="4">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {

		$hiddenFields = $form->input( "Decisionsaisinebilanparcoursep66.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Saisinebilanparcoursep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.saisinebilanparcoursep66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisinebilanparcoursep66']['id'] ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
				@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
				(!empty($dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'])) ? __d('bilanparcours66', 'ENUM::REORIENTATION::'.$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'], true) : '',
				@$dossierep['Saisinebilanparcoursep66']['Typeorient']['lib_type_orient'],
				@$dossierep['Saisinebilanparcoursep66']['Structurereferente']['lib_struc'],

				array( $form->input( "Decisionsaisinebilanparcoursep66.{$i}.decision", array( 'label' => false, 'options' => @$options['Decisionsaisinebilanparcoursep66']['decision'], 'empty' => true ) ), array( 'id' => "Decisionsaisinebilanparcoursep66{$i}DecisionColumn" ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.referent_id", array( 'label' => false, 'options' => $referents, 'empty' => true, 'type' => 'select' ) ),
// 				array( $form->input( "Decisionsaisinebilanparcoursep66.{$i}.raisonnonpassage", array( 'label' => false, 'empty' => true, 'type' => 'textarea' ) ), array( 'colspan' => 3 ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.checkcomm", array( 'label' =>false, 'type' => 'checkbox' ) ).
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
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
			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>TypeorientId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }
			
			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

			$('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').observe( 'change', function() {
				if ($('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').checked==true) {
					$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').show();
				}
				else {
					$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').hide();
				}
			} );

			if ($('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').checked==true) {
				$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').show();
			}
			else {
				$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').hide();
			}

			observeDisableFieldsOnValue(
				'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision',
				[
					'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId',
					'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId',
					'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId'
				],
				'accepte',
				false
			);

			$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanAnnuleReporte( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', 4, 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ] );
			});
			changeColspanAnnuleReporte( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', 4, 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ] );

// 			$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
// 			});
// 			afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
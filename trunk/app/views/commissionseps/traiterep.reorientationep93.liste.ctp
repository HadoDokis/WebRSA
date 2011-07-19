<?php
echo '<table><thead>
<tr>
<th>Personne</th>
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
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {

		$hiddenFields = $form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionreorientationep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionreorientationep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) );

		echo $xhtml->tableCells(
			array(
				$dossierep['Personne']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Reorientationep93']['Motifreorientep93']['name'],
				$dossierep['Reorientationep93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Reorientationep93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Reorientationep93']['Typeorient']['lib_type_orient'],
				@$dossierep['Reorientationep93']['Structurereferente']['lib_struc'],

				array(
					$form->input( "Decisionreorientationep93.{$i}.decision", array( 'label' => false, 'type' => 'select', 'options' => @$options['Decisionreorientationep93']['decision'], 'empty' => true ) ),
					array( 'id' => "Decisionreorientationep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionreorientationep93.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionreorientationep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionreorientationep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
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
			dependantSelect( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId', 'Decisionreorientationep93<?php echo $i?>TypeorientId' );
			try { $( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionreorientationep93<?php echo $i;?>Decision',
				[ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ],
				'accepte',
				false
			);

			$( 'Decisionreorientationep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionreorientationep93<?php echo $i;?>DecisionColumn', 3, 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionreorientationep93<?php echo $i;?>DecisionColumn', 3, 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ] );
		<?php endfor;?>
	});
</script>
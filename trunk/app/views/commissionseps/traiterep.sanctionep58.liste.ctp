<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th colspan=\'2\'>Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$hiddenFields = $form->input( "Decisionsanctionep58.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsanctionep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisionsanctionep58.{$i}.sanctionep58_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsanctionep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$options['Sanctionep58']['origine'][$dossierep['Sanctionep58']['origine']],

				array(
					$form->input( "Decisionsanctionep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionep58']['decision'] ) ),
					array( 'id' => "Decisionsanctionep58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionsanctionep58.{$i}.listesanctionep58_id", array( 'type' => 'select', 'label' => false, 'options' => $listesanctionseps58 ) ),
					( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['listesanctionep58_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionsanctionep58.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
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

			$( 'Decisionsanctionep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionep58<?php echo $i;?>Decision', [ 'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionep58<?php echo $i;?>Decision', [ 'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id' ] );

			observeDisableFieldsOnValue(
				'Decisionsanctionep58<?php echo $i;?>Decision',
				[
					'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id'
				],
				'sanction',
				false
			);

		<?php endfor;?>
	});
</script>
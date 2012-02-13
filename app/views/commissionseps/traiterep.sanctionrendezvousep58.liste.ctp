<?php
echo '<table><thead>
<tr>
	<th>Nom du demandeur</th>
	<th>Adresse</th>
	<th>Date de naissance</th>
	<th>Création du dossier EP</th>
	<th>Origine du dossier</th>
	<th colspan=\'5\'>Avis EPL</th>
	<th>Observations</th>
</tr>
<tr>
	<th colspan="5"></th>
	<th colspan="2">Sanction n°1</th>
	<th colspan="2">Sanction n°2</th>
	<th colspan="2"></th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$hiddenFields = $form->input( "Decisionsanctionrendezvousep58.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsanctionrendezvousep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisionsanctionrendezvousep58.{$i}.sanctionep58_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsanctionrendezvousep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsanctionrendezvousep58.{$i}.user_id", array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Sanctionrendezvousep58']['Rendezvous']['Typerdv']['motifpassageep'],

				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionrendezvousep58']['decision'], 'value' => @$dossierep['Sanctionep58']['Decisionsanctionrendezvousep58'][0]['decision'] ) ),
					array( 'id' => "Decisionsanctionrendezvousep58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionsanctionrendezvousep58'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.listesanctionep58_id", array( 'type' => 'select', 'label' => false, 'options' => $listesanctionseps58 ) ),
					( !empty( $this->validationErrors['Decisionsanctionrendezvousep58'][$i]['listesanctionep58_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				//Ajout de la possibilité de saisir une 2ème sanction lors de l'émission de la décision en EP
				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.decision2", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionrendezvousep58']['decision'], 'value' => @$dossierep['Sanctionep58']['Decisionsanctionrendezvousep58'][0]['decision2'] ) ),
					array( 'id' => "Decisionsanctionrendezvousep58{$i}DecisionColumn2", 'class' => ( !empty( $this->validationErrors['Decisionsanctionrendezvousep58'][$i]['decision2'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.autrelistesanctionep58_id", array( 'type' => 'select', 'label' => false, 'options' => $listesanctionseps58 ) ),
					( !empty( $this->validationErrors['Decisionsanctionrendezvousep58'][$i]['autrelistesanctionep58_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.regularisation", array( 'type' => 'select', 'label' => false, 'options' => @$options['Decisionsanctionrendezvousep58']['regularisation'], 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionsanctionrendezvousep58'][$i]['regularisation'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionsanctionrendezvousep58.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
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

			$( 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionsanctionrendezvousep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision', [ 'Decisionsanctionrendezvousep58<?php echo $i;?>Listesanctionep58Id' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionsanctionrendezvousep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision', [ 'Decisionsanctionrendezvousep58<?php echo $i;?>Listesanctionep58Id' ] );

			$( 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision2' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionsanctionrendezvousep58<?php echo $i;?>DecisionColumn2', 2, 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision2', [ 'Decisionsanctionrendezvousep58<?php echo $i;?>Autrelistesanctionep58Id' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionsanctionrendezvousep58<?php echo $i;?>DecisionColumn2', 2, 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision2', [ 'Decisionsanctionrendezvousep58<?php echo $i;?>Autrelistesanctionep58Id' ] );

			observeDisableFieldsOnValue(
				'Decisionsanctionrendezvousep58<?php echo $i;?>Decision',
				[
					'Decisionsanctionrendezvousep58<?php echo $i;?>Listesanctionep58Id'
				],
				'sanction',
				false
			);
			observeDisableFieldsOnValue(
				'Decisionsanctionrendezvousep58<?php echo $i;?>Decision2',
				[
					'Decisionsanctionrendezvousep58<?php echo $i;?>Autrelistesanctionep58Id'
				],
				'sanction',
				false
			);
		<?php endfor;?>
	});
</script>
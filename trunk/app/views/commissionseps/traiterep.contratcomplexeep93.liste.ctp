<?php
echo '<table><thead>
<tr>
<th rowspan="2">Personne</th>
<th rowspan="2">Nom du demandeur</th>
<th rowspan="2">Adresse</th>
<th rowspan="2">Date de naissance</th>
<th rowspan="2">Création du dossier EP</th>
<th rowspan="2">Date de début du contrat</th>
<th rowspan="2">Date de fin du contrat</th>
<th colspan="3">Avis EP</th>
<th rowspan="2">Observations</th>
</tr>
<tr>
<th>Décision</th>
<th>Date de validation</th>
<th>Observations du contrat</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$hiddenFields = $form->input( "Decisioncontratcomplexeep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisioncontratcomplexeep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisioncontratcomplexeep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisioncontratcomplexeep93.{$i}.user_id", array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );

		echo $xhtml->tableCells(
			array(
				$dossierep['Personne']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['df_ci'] ),

				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.decision", array( 'type' => 'select', 'options' => $options['Decisioncontratcomplexeep93']['decision'], 'div' => false, 'label' => false ) ),
					array( 'id' => "Decisioncontratcomplexeep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.datevalidation_ci", array( 'type' => 'date', /*'div' => false,*/ 'label' => false, 'dateFormat' => __( 'Locale->dateFormat', true ) ) ),
					( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['datevalidation_ci'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.observ_ci", array( 'type' => 'textarea', /*'div' => false, */'label' => false ) ),
					( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['observ_ci'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisioncontratcomplexeep93.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
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
			observeDisableFieldsOnValue(
				'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
				[ 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
				'valide',
				false
			);

			$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps(
				'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn',
					3,
					'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
					[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay' ]
				);
			});
			changeColspanFormAnnuleReporteEps(
				'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn',
				3,
				'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
				[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay' ]
			);
		<?php endfor;?>
	});
</script>
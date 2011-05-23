<?php
echo '<table><thead>
<tr>
<th rowspan="2">Dossier EP</th>
<th rowspan="2">Nom du demandeur</th>
<th rowspan="2">Adresse</th>
<th rowspan="2">Date de naissance</th>
<th rowspan="2">Création du dossier EP</th>
<th rowspan="2">Date de début du contrat</th>
<th rowspan="2">Date de fin du contrat</th>
<th rowspan="2">Avis EP</th>
<th colspan="4">Décision CG</th>
<th rowspan="2">Observations</th>
</tr>
<tr>
<th>Décision PCG</th>
<th>Décision</th>
<th>Date de validation</th>
<th>Observations du contrat</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$indexDecision = count( $dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'] ) - 1;
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['df_ci'] ),
				implode(
					' - ',
					Set::filter(
						array(
							Set::enum( @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['decision'], $options['Decisioncontratcomplexeep93']['decision'] ),
							$locale->date( 'Locale->date', @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['datevalidation_ci'] ),
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['observ_ci'],
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['raisonnonpassage']
						)
					)
				),

				$form->input( "Decisioncontratcomplexeep93.{$i}.decisionpcg", array( 'legend' => false, 'options' => @$options['Decisionreorientationep93']['decisionpcg'], 'type' => 'radio' ) ),
				$form->input( "Contratcomplexeep93.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Contratcomplexeep93.{$i}.dossierep_id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisioncontratcomplexeep93.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisioncontratcomplexeep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisioncontratcomplexeep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Decisioncontratcomplexeep93.{$i}.decision", array( 'type' => 'select', 'options' => $options['Decisioncontratcomplexeep93']['decision'], 'label' => false ) ),
				$form->input( "Decisioncontratcomplexeep93.{$i}.datevalidation_ci", array( 'type' => 'date', 'label' => false, 'dateFormat' => __( 'Locale->dateFormat', true ) ) ),
				$form->input( "Decisioncontratcomplexeep93.{$i}.observ_ci", array( 'type' => 'textarea', 'label' => false ) ),
				array( $form->input( "Decisioncontratcomplexeep93.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea', 'empty' => true ) ), array( 'colspan' => '2' ) )
			)
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

			$( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionpcgEnattente' ).observe( 'click', function() {
				$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).setValue( 'reporte' );
				fireEvent( $( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ), 'change' );
			} );

			$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				afficheRaisonpassage(
					'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
					[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
					'Decisioncontratcomplexeep93<?php echo $i;?>Raisonnonpassage'
				);
			});
			afficheRaisonpassage(
				'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
				[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
				'Decisioncontratcomplexeep93<?php echo $i;?>Raisonnonpassage'
			);
		<?php endfor;?>
	});
</script>
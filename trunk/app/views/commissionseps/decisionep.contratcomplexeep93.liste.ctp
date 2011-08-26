<?php
echo '<table><thead>
<tr>
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
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = $dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][0];

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['df_ci'] ),

				array( $options['Decisioncontratcomplexeep93']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisioncontratcomplexeep93{$i}DecisionColumn" ) ),
				array( Set::classicExtract( $decisionep, "datevalidation_ci" ), array( 'id' => "Decisioncontratcomplexeep93{$i}DatevalidationCi" ) ),
				array( Set::classicExtract( $decisionep, "observ_ci" ), array( 'id' => "Decisioncontratcomplexeep93{$i}ObservCi" ) ),
				Set::classicExtract( $decisionep, "commentaire" )
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
			changeColspanViewInfosEps( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisioncontratcomplexeep93.0.decision" );?>', 3, [ 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCi', 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi' ] );
		<?php endfor;?>
	});
</script>
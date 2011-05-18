<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th colspan=\'2\'>Avis EPL</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsanctionep58'][0];
		
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				__d( 'sanctionep58', $dossierep['Sanctionep58']['origine'], true),

				$options['Decisionsanctionep58']['decision'][Set::classicExtract( $decisionep, "decision" )],
				array( $listesanctionseps58[Set::classicExtract( $decisionep, "listesanctionep58_id" )], array( 'id' => "Decisionsanctionep58{$i}Listesanctionep58Id" ) ),
				array( Set::classicExtract( $decisionep, "raisonnonpassage" ), array( 'id' => "Decisionsanctionep58{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo $options['Decisionsanctionep58']['decision'][Set::classicExtract( $decisionep, "decision" )];?>', [ 'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id' ], 'Decisionsanctionep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
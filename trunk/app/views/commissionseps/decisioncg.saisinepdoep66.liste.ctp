<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif(s) de la PDO</th>
<th>Description du traitement</th>
<th colspan=\'1\'>Avis de l\'EP</th>
<th colspan=\'2\'>Décision du CG</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0];

		$listeSituationPdo = array();
		foreach($dossierep['Saisinepdoep66']['Traitementpdo']['Propopdo']['Situationpdo'] as $situationpdo) {
			$listeSituationPdo[] = $situationpdo['libelle'];
		}

		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				implode(' / ', $listeSituationPdo),
				$dossierep['Saisinepdoep66']['Traitementpdo']['Descriptionpdo']['name'],

				$options['Decisionsaisinepdoep66']['decision'][Set::classicExtract( $decisioncg, "decision" )],

				@$decisionep['Decisionpdo']['libelle'],
// 				$decisionep['commentaire'],
				array( @$options['Decisionsaisinepdoep66']['decisionpdo_id'][Set::classicExtract( $decisioncg, "decisionpdo_id" )].' au '.Set::classicExtract( $decisioncg, "datedecisionpdo" ), array( 'id' => "Decisionsaisinepdoep66{$i}Decisioncg" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'id' => "Decisionsaisinepdoep66{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';

?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinepdoep66.0.decision" );?>', [ 'Decisionsaisinepdoep66<?php echo $i;?>Decisioncg' ], 'Decisionsaisinepdoep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
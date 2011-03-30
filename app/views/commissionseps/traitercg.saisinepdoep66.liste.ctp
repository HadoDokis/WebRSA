<?php

foreach( $dossiers['Saisinepdoep66']['liste'] as $key => $dossierep ) {
	$formData['Saisinepdoep66'][$key]['id'] = $dossierep['Saisinepdoep66']['id'];
	$listeSituationPdo = array();
	foreach($dossierep['Saisinepdoep66']['Traitementpdo']['Propopdo']['Situationpdo'] as $situationpdo) {
		$listeSituationPdo[] = $situationpdo['libelle'];
	}
	if (!empty($listeSituationPdo))
		$formData['Saisinepdoep66'][$key]['Situationpdo'] = implode(' / ', $listeSituationPdo);
	else
		$formData['Saisinepdoep66'][$key]['Situationpdo'] = '';
	$formData['Saisinepdoep66'][$key]['Descriptionpdo'] = $dossierep['Saisinepdoep66']['Traitementpdo']['Descriptionpdo']['name'];
	$formData['Dossierep'][$key]['id'] = $dossierep['Dossierep']['id'];
}
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif(s) de la PDO</th>
<th>Description du traitement</th>
<th>Avis de l\'EP</th>
<th>Commentaire de l\'EP</th>
<th>CGA s\'est prononcé ?</th>
<th>Actions</th>
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
				$formData['Saisinepdoep66'][$i]['Situationpdo'],
				$formData['Saisinepdoep66'][$i]['Descriptionpdo'],
				$dossierep['Saisinepdoep66']['Decisionsaisinepdoep66'][0]['Decisionpdo']['libelle'],
				$dossierep['Saisinepdoep66']['Decisionsaisinepdoep66'][0]['commentaire'],
				isset($dossierep['Saisinepdoep66']['Decisionsaisinepdoep66'][1]) ? 'Oui' : 'Non',
				$xhtml->link(
					'Décision',
					array(
						'controller'=>'dossierseps',
						'action'=>'decisioncg',
						$dossierep['Dossierep']['id']
					)
				)
			)
		);
	}
	echo '</tbody></table>';

?>

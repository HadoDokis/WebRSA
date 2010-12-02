<?php

foreach( $dossiers['Saisineepdpdo66']['liste'] as $key => $dossierep ) {
	$formData['Saisineepdpdo66'][$key]['id'] = $dossierep['Saisineepdpdo66']['id'];
	$listeSituationPdo = array();
	foreach($dossierep['Saisineepdpdo66']['Traitementpdo']['Propopdo']['Situationpdo'] as $situationpdo) {
		$listeSituationPdo[] = $situationpdo['libelle'];
	}
	if (!empty($listeSituationPdo))
		$formData['Saisineepdpdo66'][$key]['Situationpdo'] = implode(' / ', $listeSituationPdo);
	else
		$formData['Saisineepdpdo66'][$key]['Situationpdo'] = '';
	$formData['Saisineepdpdo66'][$key]['Descriptionpdo'] = $dossierep['Saisineepdpdo66']['Traitementpdo']['Descriptionpdo']['name'];
	$formData['Dossierep'][$key]['id'] = $dossierep['Dossierep']['id'];
}
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Qualité</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif(s) de la PDO</th>
<th>Description du traitement</th>
<th>Avis de l\'EP</th>
<th>Commentaire de l\'EP</th>
<th>CGA s\'est prononcé ?</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$xhtml->link(
					$dossierep['Dossierep']['id'],
					array(
						'controller'=>'dossierseps',
						'action'=>'decisioncg',
						$dossierep['Dossierep']['id']
					)
				),
				$dossierep['Personne']['qual'],
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$formData['Saisineepdpdo66'][$i]['Situationpdo'],
				$formData['Saisineepdpdo66'][$i]['Descriptionpdo'],
				$dossierep['Saisineepdpdo66']['Nvsepdpdo66'][0]['Decisionpdo']['libelle'],
				$dossierep['Saisineepdpdo66']['Nvsepdpdo66'][0]['commentaire'],
				isset($dossierep['Saisineepdpdo66']['Nvsepdpdo66'][1]) ? 'Oui' : 'Non'
			)
		);
	}
	echo '</tbody></table>';

?>

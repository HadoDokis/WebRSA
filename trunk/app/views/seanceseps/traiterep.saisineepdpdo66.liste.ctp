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

	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Qualité</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif(s) de la PDO</th>
<th>Description du traitement</th>
<th>Avis de l\'EP</th>
<th>Commentaire</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				$dossierep['Personne']['qual'],
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$formData['Saisineepdpdo66'][$i]['Situationpdo'],
				$formData['Saisineepdpdo66'][$i]['Descriptionpdo'],
				$form->input( "Nvsepdpdo66.{$i}.decisionpdo_id", array( 'label' => false, 'options' => @$options['Seanceep']['decisionpdo_id'], 'empty' => true ) ),
				$form->input( "Nvsepdpdo66.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea', 'cols' => '25', 'rows' => '2' ) ).
				$form->input( "Nvsepdpdo66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Saisineepdpdo66']['Nvsepdpdo66'][$i]['id'] ) ).
				$form->input( "Nvsepdpdo66.{$i}.saisineepdpdo66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisineepdpdo66']['id'] ) ).
				$form->input( "Nvsepdpdo66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Saisineepdpdo66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Saisineepdpdo66'][$i]['id'] ) ).
				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) )
			)
		);
	}
	echo '</tbody></table>';
	echo $form->submit( 'Enregistrer' );
	echo $form->end();

?>

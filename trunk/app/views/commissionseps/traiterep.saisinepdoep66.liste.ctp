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
<th colspan=\'3\'>Avis de l\'EP</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
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
				$form->input( "Decisionsaisinepdoep66.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionsaisinepdoep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionsaisinepdoep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisionsaisinepdoep66.{$i}.decision", array( 'label' => false, 'options' => $options['Decisionsaisinepdoep66']['decision'], 'empty' => true ) ),
				$form->input( "Decisionsaisinepdoep66.{$i}.decisionpdo_id", array( 'label' => false, 'options' => @$options['Decisionsaisinepdoep66']['decisionpdo_id'], 'empty' => true ) ),
				$form->input( "Decisionsaisinepdoep66.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ),
				array( $form->input( "Decisionsaisinepdoep66.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => 2 ) ),
				$form->input( "Decisionsaisinepdoep66.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) )
			),
			array( 'class' => 'odd' ),
			array( 'class' => 'even' )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			observeDisableFieldsOnValue(
				'Decisionsaisinepdoep66<?php echo $i;?>Decision',
				[
					'Decisionsaisinepdoep66<?php echo $i;?>DecisionpdoId',
					'Decisionsaisinepdoep66<?php echo $i;?>Commentaire'
				],
				'avis',
				false
			);

			$( 'Decisionsaisinepdoep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				afficheRaisonpassage( 'Decisionsaisinepdoep66<?php echo $i;?>Decision', [ 'Decisionsaisinepdoep66<?php echo $i;?>DecisionpdoId', 'Decisionsaisinepdoep66<?php echo $i;?>Commentaire' ], 'Decisionsaisinepdoep66<?php echo $i;?>Raisonnonpassage' );
			});
			afficheRaisonpassage( 'Decisionsaisinepdoep66<?php echo $i;?>Decision', [ 'Decisionsaisinepdoep66<?php echo $i;?>DecisionpdoId', 'Decisionsaisinepdoep66<?php echo $i;?>Commentaire' ], 'Decisionsaisinepdoep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
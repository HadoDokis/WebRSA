<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th colspan=\'2\'>Avis EPL</th>
<th>Si sanction</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
// debug( $dossierep );
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				__d( 'sanctionep58', $dossierep['Sanctionep58']['origine'], true),

				array(
					$form->input( "Sanctionep58.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Sanctionep58']['id'] ) ).
					$form->input( "Sanctionep58.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
					$form->input( "Decisionsanctionep58.{$i}.id", array( 'type' => 'hidden', 'value' => @$dossierep['Sanctionep58']['Decisionsanctionep58'][0]['id'] ) ).
					$form->input( "Decisionsanctionep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
					$form->input( "Decisionsanctionep58.{$i}.sanctionep58_id", array( 'type' => 'hidden', 'value' => $dossierep['Sanctionep58']['id'] ) ).

					$form->input( "Decisionsanctionep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionep58']['decision'], 'value' => @$dossierep['Sanctionep58']['Decisionsanctionep58'][0]['decision'] ) ),
					array( 'id' => "Sanction{$i}Choose", 'colspan' => 2 )
				),
				$form->input( "Decisionsanctionep58.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea', 'empty' => true ) ),
				$listesanctionseps58[$dossierep['Sanctionep58']['listesanctionep58_id']]
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $commissionep );
// debug( $dossiers[$theme]['liste'] );
// debug( $options );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			$( 'Decisionsanctionep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspan( 'Sanction<?php echo $i;?>Choose', 'Decisionsanctionep58<?php echo $i;?>Decision', [ ], 'Decisionsanctionep58<?php echo $i;?>Raisonnonpassage' );
			});
			changeColspan( 'Sanction<?php echo $i;?>Choose', 'Decisionsanctionep58<?php echo $i;?>Decision', [ ], 'Decisionsanctionep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
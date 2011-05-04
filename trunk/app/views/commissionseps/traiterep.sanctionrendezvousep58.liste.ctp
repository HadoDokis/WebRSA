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
</tr>
</thead><tbody>';
// debug($this->data);
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
// debug( $dossierep );
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				Set::classicExtract( $typesrdv, $dossierep['Sanctionrendezvousep58']['Rendezvous']['typerdv_id'], true),

				array(
					$form->input( "Decisionsanctionrendezvousep58.{$i}.id", array( 'type' => 'hidden' ) ).
					$form->input( "Decisionsanctionrendezvousep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
					$form->input( "Decisionsanctionrendezvousep58.{$i}.sanctionep58_id", array( 'type' => 'hidden' ) ).
					$form->input( "Decisionsanctionrendezvousep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).

					$form->input( "Decisionsanctionrendezvousep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionrendezvousep58']['decision'], 'value' => @$dossierep['Sanctionep58']['Decisionsanctionrendezvousep58'][0]['decision'] ) ),
					array( 'id' => "Decisionsanctionrendezvousep58{$i}ColumnDecision", 'colspan' => 2 )
				),
				$form->input( "Decisionsanctionrendezvousep58.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) )
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
			$( 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanRaisonNonPassage( 'Decisionsanctionrendezvousep58<?php echo $i;?>ColumnDecision', 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision', [ ], 'Decisionsanctionrendezvousep58<?php echo $i;?>Raisonnonpassage' );
			});
			changeColspanRaisonNonPassage( 'Decisionsanctionrendezvousep58<?php echo $i;?>ColumnDecision', 'Decisionsanctionrendezvousep58<?php echo $i;?>Decision', [ ], 'Decisionsanctionrendezvousep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
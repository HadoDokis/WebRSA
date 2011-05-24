<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date de début du contrat</th>
<th>Date de fin du contrat</th>
<th>Date de signalement</th>
<th>Motif de passage en EP</th>
<th>Rang du passage en EP</th>
<th>Situation familiale</th>
<th>Nombre d\'enfants</th>
<th colspan="2">Avis EP</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$lineOptions = array();
		foreach( $options['Decisionsignalementep93']['decision'] as $key => $label ) {
			if( !in_array( $key[0], array( 1, 2 ) ) || ( $key[0] == min( 2, $dossierep['Signalementep93']['rang'] ) ) ) {
				$lineOptions[$key] = $label;
			}
		}

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['Contratinsertion']['df_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['date'] ),
				@$dossierep['Signalementep93']['motif'],
				@$dossierep['Signalementep93']['rang'],
				Set::enum( @$dossierep['Personne']['Foyer']['sitfam'], $options['Foyer']['sitfam'] ),
				@$dossierep['Personne']['Foyer']['nbenfants'],
				array(
					$form->input( "Signalementep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Signalementep93']['id']*/ ) ).
					$form->input( "Signalementep93.{$i}.dossierep_id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Dossierep']['id']*/ ) ).
					$form->input( "Decisionsignalementep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => @$record['id']*/ ) ).
					$form->input( "Decisionsignalementep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
					$form->input( "Decisionsignalementep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
					$form->input( "Decisionsignalementep93.{$i}.decision", array( 'type' => 'select', 'options' => $lineOptions, 'div' => false, 'label' => false ) ),
					array( 'id' => "Decisionsignalementep93{$i}ColumnDecision", 'colspan' => 2 )
				),
				$form->input( "Decisionsignalementep93.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ),
				$form->input( "Decisionsignalementep93.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) )
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
			$( 'Decisionsignalementep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanRaisonNonPassage( 'Decisionsignalementep93<?php echo $i;?>ColumnDecision', 'Decisionsignalementep93<?php echo $i;?>Decision', [], 'Decisionsignalementep93<?php echo $i;?>Raisonnonpassage' );
			});
			changeColspanRaisonNonPassage( 'Decisionsignalementep93<?php echo $i;?>ColumnDecision', 'Decisionsignalementep93<?php echo $i;?>Decision', [], 'Decisionsignalementep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
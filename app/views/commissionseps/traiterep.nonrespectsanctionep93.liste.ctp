<?php
echo '<table><thead>
<tr>
<th>Personne</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th>Date d\'orientation</th>
<th>Rang du passage en EP</th>
<th>Situation familiale</th>
<th>Nombre d\'enfants</th>
<th>Avis EP</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$lineOptions = array();
		foreach( $options['Decisionnonrespectsanctionep93']['decision'] as $key => $label ) {
			if( !in_array( $key[0], array( 1, 2 ) ) || ( $key[0] == min( 2, $dossierep['Nonrespectsanctionep93']['rgpassage'] ) ) ) {
				$lineOptions[$key] = $label;
			}
		}

		$hiddenFields = $form->input( "Decisionnonrespectsanctionep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonrespectsanctionep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonrespectsanctionep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$form->input( "Decisionnonrespectsanctionep93.{$i}.user_id", array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );

		echo $xhtml->tableCells(
			array(
				$dossierep['Personne']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				Set::enum( @$dossierep['Nonrespectsanctionep93']['origine'], $options['Nonrespectsanctionep93']['origine'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Nonrespectsanctionep93']['Orientstruct']['date_valid'] ),
				@$dossierep['Nonrespectsanctionep93']['rgpassage'],
				Set::enum( @$dossierep['Personne']['Foyer']['sitfam'], $options['Foyer']['sitfam'] ),
				@$dossierep['Personne']['Foyer']['nbenfants'],

				array(
					$form->input( "Decisionnonrespectsanctionep93.{$i}.decision", array( 'type' => 'select', 'options' => $lineOptions, 'div' => false, 'label' => false ) ),
					array( 'id' => "Decisionnonrespectsanctionep93{$i}DecisionColumn" ), 'class' => ( !empty( $this->validationErrors['Decisionnonrespectsanctionep93'][$i]['decision'] ) ? 'error' : '' )
				),

				$form->input( "Decisionnonrespectsanctionep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields
			),
			array( 'class' => "odd {$multiple}" ),
			array( 'class' => "even {$multiple}" )
		);
	}
	echo '</tbody></table>';
?>
<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date de radiation</th>
<th>Motif de radiation</th>
<th>Avis EPL</th>
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
				$locale->date( __( 'Locale->date', true ), @$dossierep['Radiepoleemploiep58']['Historiqueetatpe']['date'] ),
				@$dossierep['Radiepoleemploiep58']['Historiqueetatpe']['motif'],

				$form->input( "Radiepoleemploiep58.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Radiepoleemploiep58']['id'] ) ).
				$form->input( "Radiepoleemploiep58.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisionradiepoleemploiep58.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionradiepoleemploiep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisionradiepoleemploiep58.{$i}.radiepoleemploiep58_id", array( 'type' => 'hidden', 'value' => $dossierep['Radiepoleemploiep58']['id'] ) ).

				$form->input( "Decisionradiepoleemploiep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionradiepoleemploiep58']['decision'], 'value' => @$decisionsdefautsinsertionseps66[$i]['decision'] ) ),
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $seanceep );
// debug( $dossiers[$theme]['liste'] );
// debug( $options );
?>
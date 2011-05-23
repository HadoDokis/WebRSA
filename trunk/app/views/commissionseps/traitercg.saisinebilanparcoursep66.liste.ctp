<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Type de réorientation</th>
<th>Proposition référent</th>
<th>Avis EPL</th>
<th>Avis CG</th>
<th colspan="3">Décision coordonnateur/CG</th>
<th>Observations</th>
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
				$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
				(!empty($dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'])) ? __d('bilanparcours66', 'ENUM::REORIENTATION::'.$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'], true) : '',
	
				implode( ' - ', Set::filter( array( @$dossierep['Saisinebilanparcoursep66']['Typeorient']['lib_type_orient'], @$dossierep['Saisinebilanparcoursep66']['Structurereferente']['lib_struc'] ) ) ),

				implode( ' - ', Set::filter( array( @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['Typeorient']['lib_type_orient'], @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['Structurereferente']['lib_struc'] ) ) ),

				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data['Decisionsaisinebilanparcoursep66'][$i]['id'] ) ).
				$form->input( "Saisinebilanparcoursep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.decision", array( 'label' => false, 'options' => @$options['Decisionsaisinebilanparcoursep66']['decision'], 'empty' => true ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.referent_id", array( 'label' => false, 'options' => $referents, 'empty' => true ) ),
				array( $form->input( "Decisionsaisinebilanparcoursep66.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => 3 ) ),
				$form->input( "Decisionsaisinebilanparcoursep66.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>TypeorientId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }
			
			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision',
				[ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ],
				'accepte',
				false
			);

			$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
			});
			afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
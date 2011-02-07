<?php
	echo '<table><thead><tr>';
	
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.id', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.adresse', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.dtnai', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', $theme.'.datedemande', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Dossiercov58.proporeferent', true ), array( 'colspan' => 2 ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.decisioncov', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Dossiercov58.choixcov', true ), array( 'colspan' => 2 ) );
	
echo '</tr></thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossiercov ) {
		echo $form->input( "{$theme}.{$i}.id", array( 'type' => 'hidden', 'value' => $dossiercov[$theme][0]['id'] ) );
		echo $xhtml->tableCells(
			array(
				$dossiercov['Dossiercov58']['id'],
				implode( ' ', array( $dossiercov['Personne']['qual'], $dossiercov['Personne']['nom'], $dossiercov['Personne']['prenom'] ) ),
				implode( ' ', array( $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossiercov['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossiercov[$theme][0]['datedemande'] ),
				$dossiercov[$theme][0]['Typeorient']['lib_type_orient'],
				$dossiercov[$theme][0]['Structurereferente']['lib_struc'],
				$form->input( "{$theme}.{$i}.decisioncov", array( 'type' => 'select', 'options' => $decisionscovs, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.typeorient_id", array( 'type' => 'select', 'options' => $typesorients, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.structurereferente_id", array( 'type' => 'select', 'options' => $structuresreferentes, 'label' => false, 'empty' => true ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Propoorientationcov58<?php echo $i?>StructurereferenteId', 'Propoorientationcov58<?php echo $i?>TypeorientId' );
			try { $( 'Propoorientationcov58<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Propoorientationcov58<?php echo $i;?>Decisioncov',
				[ 'Propoorientationcov58<?php echo $i;?>TypeorientId', 'Propoorientationcov58<?php echo $i;?>StructurereferenteId' ],
				'refus',
				false
			);
		<?php endfor;?>
	});
</script>
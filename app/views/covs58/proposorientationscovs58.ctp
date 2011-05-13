<?php
	echo '<table><thead><tr>';
	
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nir', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
	echo $xhtml->tag( 'th', __d( 'personne', 'Personne.adresse', true ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.datedemande', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.proporeferent', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.decisioncov', true ) );
	echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.choixcov', true ), array( 'colspan' => 3 ) );
	echo $xhtml->tag( 'th', __d( Inflector::underscore(Inflector::classify($theme)), $theme.'.commentaire', true ) );
	echo $xhtml->tag( 'th', 'Actions' );
	
echo '</tr></thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossiercov ) {
		echo $form->input( "{$theme}.{$i}.id", array( 'type' => 'hidden', 'value' => $dossiercov[$theme]['id'] ) );
		echo $xhtml->tableCells(
			array(
				$dossiercov['Personne']['nir'],
				implode( ' ', array( $dossiercov['Personne']['qual'], $dossiercov['Personne']['nom'], $dossiercov['Personne']['prenom'] ) ),
				implode( ' ', array( $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossiercov['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossiercov[$theme]['datedemande'] ),
				implode( ' - ', Set::filter( array( $dossiercov['Typeorient']['lib_type_orient'], $dossiercov['Structurereferente']['lib_struc'], implode( ' ', Set::filter( array( $dossiercov['Referent']['qual'], $dossiercov['Referent']['nom'], $dossiercov['Referent']['prenom'] ) ) ) ) ) ),
				$form->input( "{$theme}.{$i}.decisioncov", array( 'type' => 'select', 'options' => $decisionscovs, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.typeorient_id", array( 'type' => 'select', 'options' => $typesorients, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.structurereferente_id", array( 'type' => 'select', 'options' => $structuresreferentes, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.referent_id", array( 'type' => 'select', 'options' => $referents, 'label' => false, 'empty' => true ) ),
				$form->input( "{$theme}.{$i}.commentaire", array( 'type' => 'textarea', 'label' => false ) ),
				$xhtml->viewLink( 'Voir', array( 'controller' => 'orientsstructs', 'action' => 'index', $dossiercov['Personne']['id'] ), true, true )
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
			
			dependantSelect( 'Propoorientationcov58<?php echo $i?>ReferentId', 'Propoorientationcov58<?php echo $i?>StructurereferenteId' );
			try { $( 'Propoorientationcov58<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Propoorientationcov58<?php echo $i;?>Decisioncov',
				[ 'Propoorientationcov58<?php echo $i;?>TypeorientId', 'Propoorientationcov58<?php echo $i;?>StructurereferenteId', 'Propoorientationcov58<?php echo $i;?>ReferentId' ],
				'refus',
				false
			);
		<?php endfor;?>
	});
</script>
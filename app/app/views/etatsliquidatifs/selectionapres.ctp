<?php
	$this->pageTitle = 'Sélection des APREs pour l\'état liquidatif';

	echo $html->tag( 'h1', $this->pageTitle );

	if( empty( $apres ) ) {
		echo $html->tag( 'p', 'Aucune APRE à sélectionner.', array( 'class' => 'notice' ) );
	}
	else {
		$headers = array(
// 			'Dossier.numdemrsa',
// 			'Apre.numeroapre',
// 			'Apre.datedemandeapre',
// 			'Apre.mtforfait',
// 			'Apre.nbenf12',
// 			'Personne.nom',
// 			'Personne.prenom',
// 			'Adresse.locaadr',
// 			'Sélectionner'
            'N° Dossier',
            'N° APRE',
            'Date de demande APRE',
            'Montant forfaitaire',
            'Nb enfant - 12ans',
            'Nom bénéficiaire',
            'Prénom bénéficiaire',
            'Adresse',
            'Sélectionner'
		);

		///
		$thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );
		//$thead = str_replace( '</tr>', '<th colspan="2">Action</th></tr>', $thead );

		echo $xform->create( 'Etatliquidatif' );
		// FIXME
		echo '<div>'.$xform->input( 'Etatliquidatif.id', array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).'</div>';

		/// Corps du tableau
		$rows = array();
		foreach( $apres as $i => $apre ) {

            if( $typeapre == 'C' ){
                $montant = ( Set::classicExtract( $apre, 'Apre.mtforfait' ) + Set::classicExtract( $apre, 'Apre.montantaverser' ) );
            }
            else if( $typeapre == 'F' ) {
                $montant = Set::classicExtract( $apre, 'Apre.mtforfait' );
            }
// if( !empty( $montant ) ){
			$apre_id = Set::classicExtract( $apre, 'Apre.id' );
			$rows[] = array(
				Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
				Set::classicExtract( $apre, 'Apre.numeroapre' ),
				$locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
				$locale->money( $montant ),
				Set::classicExtract( $apre, 'Apre.nbenf12' ),
				Set::classicExtract( $apre, 'Personne.nom' ),
				Set::classicExtract( $apre, 'Personne.prenom' ),
				Set::classicExtract( $apre, 'Adresse.locaadr' ),
				$xform->checkbox( "Apre.Apre.$i", array( 'value' => $apre_id, 'checked' => ( in_array( $apre_id, $this->data['Apre']['Apre'] ) ), 'class' => 'checkbox' ) )
			);
		}
//         }
		$tbody = $html->tag( 'tbody', $html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		///
		echo $html->tag(
			'ul',
			implode(
				'',
				array(
					$html->tag( 'li', $html->link( 'Tout sélectionner', '#', array( 'onclick' => 'allCheckboxes( true ); return false;' ) ) ),
					$html->tag( 'li', $html->link( 'Tout désélectionner', '#', array( 'onclick' => 'allCheckboxes( false ); return false;' ) ) ),
				)
			)
		);

		echo $html->tag( 'p', sprintf( '%s APREs dans la liste', $locale->number( count( $apres ) ) ) );
		echo $html->tag( 'table', $thead.$tbody );

        $buttons = array();
        $buttons[] = $xform->submit( 'Valider la liste', array( 'div' => false ) );
        $buttons[] = $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        echo $html->tag( 'div', implode( '', $buttons ), array( 'class' => 'submit' ) );

		echo $xform->end();
	}
?>
<script type="text/javascript">
//<![CDATA[
	function allCheckboxes( checked ) {
		$$('input.checkbox').each( function ( checkbox ) {
			$( checkbox ).checked = checked;
		} );
		return false;
	}
//]]>
</script>
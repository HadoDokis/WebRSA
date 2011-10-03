<?php
	$this->pageTitle = 'Impression des APREs pour l\'état liquidatif';

	echo $xhtml->tag( 'h1', $this->pageTitle );

	///Fin pagination


	if( empty( $apres ) ) {
		echo $xhtml->tag( 'p', 'Aucune APRE à sélectionner.', array( 'class' => 'notice' ) );
	}
	else {
		$pagination = $xpaginator->paginationBlock( 'Etatliquidatif', array( $this->params['pass'][0] ) );

		$headers = array(
			$xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' ),
			$xpaginator->sort( 'N° APRE', 'Apre.numeroapre' ),
			$xpaginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' ),
			$xpaginator->sort( 'Montant forfaitaire', 'Apre.mtforfait' ),
			$xpaginator->sort( 'Nb enfant - 12ans', 'Apre.nbenf12' ),
			$xpaginator->sort( 'Nom bénéficiaire', 'Personne.nom' ),
			$xpaginator->sort( 'Prénom bénéficiaire', 'Personne.prenom' ),
			$xpaginator->sort( 'Adresse', 'Adresse.locaadr' ),
			'Formation',
			'Bénéficiaire',
			'Tiers prestataire',

		);

		///
		$thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );

		echo $xform->create( 'Etatliquidatif' );
		// FIXME
		echo '<div>'.$xform->input( 'Etatliquidatif.id', array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).'</div>';

		/// Corps du tableau
		$rows = array();
		$libelleNatureaide = null;

		foreach( $apres as $i => $apre ) {
			if( $typeapre == 'F' ) {
				$apre['Apre']['allocation'] = $apre['Apre']['mtforfait'];
				$isTiers = false;
				$dest = null;
			}
			else if( $typeapre == 'C' ) {
				$apre['Apre']['allocation'] = $apre['ApreEtatliquidatif']['montantattribue'];
				$aidesApre = array();
				$isTiers = false;
				$modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
				$natureaide = Set::classicExtract( $apre, 'Apre.nomaide' );
				if( !empty( $natureaide ) ) {
					$aidesApre = $natureaide;
					if( in_array( $natureaide, $modelsFormation ) ){
						$dest = 'tiersprestataire';
						$isTiers = true;
						$libelleNatureaide = __d( 'apre', $natureaide, true ); // FIXME: traduction
					}
					else{
						$dest = 'beneficiaire';
						$isTiers = false;
						$libelleNatureaide = 'Hors formation';
					}
				}
			}
			else {
				$this->cakeError( 'error500' );
			}

			$apre_id = Set::classicExtract( $apre, 'Apre.id' );
			$rows[] = array(
				Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
				Set::classicExtract( $apre, 'Apre.numeroapre' ),
				$locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
				$locale->money( Set::classicExtract( $apre, 'Apre.allocation' ) ),
				Set::classicExtract( $apre, 'Apre.nbenf12' ),
				Set::classicExtract( $apre, 'Personne.nom' ),
				Set::classicExtract( $apre, 'Personne.prenom' ),
				Set::classicExtract( $apre, 'Adresse.locaadr' ),
				$libelleNatureaide,
				$theme->button( 'print', array( 'action' => 'impressiongedoooapres', $apre_id, $this->params['pass'][0], 'dest' => 'beneficiaire' ) /*array( 'enabled' =>  !$isTiers )*/ ),
				$theme->button( 'print', array( 'action' => 'impressiongedoooapres', $apre_id, $this->params['pass'][0], 'dest' => 'tiersprestataire' ), array( 'enabled' =>  $isTiers ) ),
			);
		}
		$tbody = $xhtml->tag( 'tbody', $xhtml->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		echo $pagination;
		echo $xhtml->tag( 'table', $thead.$tbody, array( 'class' => 'nocssicons' ) );
		echo $pagination;

		echo $xform->end();
	}
?>
<?php if( $typeapre == 'F' ) :?>
	<ul class="actionMenu">
		<li><?php
			echo $xhtml->printCohorteLink(
				'Imprimer la cohorte',
				Set::merge(
					array(
						'action' => 'impressioncohorte',
						$this->params['pass'][0],
					),
					$this->params['named'],
					array_unisize( $this->data )
				)
			);
		?></li>
	</ul>
<?php endif;?>

<?php echo $default->button( 'back', array( 'action' => 'index' ), array( 'id' => 'Back' ) ); ?>
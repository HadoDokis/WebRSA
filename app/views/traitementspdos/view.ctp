<?php
	$this->pageTitle =  __d( 'traitementpdo', "Traitementspdos::{$this->action}", true );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $traitementpdo['Traitementpdo']['personne_id'] ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );

		echo $default2->view(
			$traitementpdo,
			array(
				'Descriptionpdo.name',
				'Traitementtypepdo.name',
				'Traitementpdo.datereception',
				'Traitementpdo.datedepart',
				'Traitementpdo.dureedepart' => array( 'type' => 'text', 'value' => $options['Traitementpdo']['dureedepart'][$traitementpdo['Traitementpdo']['dureedepart']] ),
				'Traitementpdo.dateecheance',
				'Traitementpdo.dureeecheance' => array( 'type' => 'text', 'value' => $options['Traitementpdo']['dureeecheance'][$traitementpdo['Traitementpdo']['dureeecheance']] ),
				'Traitementpdo.daterevision',
				'Personne.nom_complet' => array( 'type' => 'string', 'value' => '#Personne.nom# #Personne.prenom#' ),
			)
		);

		$fichiersParType = array(
			'Courriers' => Set::extract( $traitementpdo['Fichiertraitementpdo'], '/.[type=courrier]' ),
			'Pièces jointes' => Set::extract( $traitementpdo['Fichiertraitementpdo'], '/.[type=piecejointe]' )
		);

		foreach( $fichiersParType as $title => $fichiers ) {
			echo "<h2>{$title}</h2>";

			if( empty( $fichiers ) ) {
				echo '<p class="notice">Aucun élément.</p>';
			}
			else {
				echo '<ul>';
				foreach( $fichiers as $fichier ) {
					echo '<li>'.$xhtml->link(
						$fichier['name'],
						array(
							'action' => 'fileview',
							'edit',
							$fichier['traitementpdo_id'],
							$fichier['type'],
							urlencode( $fichier['name'] )
						)
					).'</li>';
				}
				echo '</ul>';
			}
		}

		echo '<h2>'.__d( 'traitementpdo', 'Traitementpdo.ficheanalyse', true ).'</h2>';
		if( empty( $traitementpdo['Traitementpdo']['ficheanalyse'] ) ) {
			echo '<p class="notice">Pas de fiche d\'analyse.</p>';
		}
		else {
			echo "<p>".nl2br( $traitementpdo['Traitementpdo']['ficheanalyse'] )."</p>";
		}

		echo '<h2>'.__d( 'traitementpdo', 'Traitementpdo.hasrevenu', true ).'</h2>';
		if( empty( $traitementpdo['Traitementpdo']['hasrevenu'] ) ) {
			echo '<p class="notice">Pas de revenu.</p>';
		}
		else {
			// FIXME: calculs intermédiaires, taux, ... + vérifier
			$regime = Set::enum( $traitementpdo['Traitementpdo']['regime'], $options['Traitementpdo']['regime'] );

			switch( $traitementpdo['Traitementpdo']['regime'] ) {
				case 'microbnc':
					echo $default2->view(
						$traitementpdo,
						array(
							'Traitementpdo.regime' => array( 'type' => 'text', 'value' => $regime ),
							'Traitementpdo.saisonnier' => array( 'type' => 'boolean' ),
							'Traitementpdo.nrmrcs',
							'Traitementpdo.dtdebutactivite',
							'Traitementpdo.raisonsocial',
							'Traitementpdo.dtdebutperiode',
							'Traitementpdo.dtfinperiode',
							'Traitementpdo.nbmoisactivite',
							'Traitementpdo.chaffsrv',
							// FIXME: calculs javascript
							'Traitementpdo.aidesubvreint',
							'Traitementpdo.benefpriscompte',
							'Traitementpdo.revenus',
							'Traitementpdo.dtprisecompte',
						)
					);
					break;
				case 'microbic':
				case 'microbicauto':
					echo $default2->view(
						$traitementpdo,
						array(
							'Traitementpdo.regime' => array( 'type' => 'text', 'value' => $regime ),
							'Traitementpdo.saisonnier' => array( 'type' => 'boolean' ),
							'Traitementpdo.nrmrcs',
							'Traitementpdo.dtdebutactivite',
							'Traitementpdo.raisonsocial',
							'Traitementpdo.dtdebutperiode',
							'Traitementpdo.dtfinperiode',
							'Traitementpdo.nbmoisactivite',
							'Traitementpdo.chaffvnt',
							'Traitementpdo.chaffsrv',
							// FIXME: calculs javascript
							'Traitementpdo.aidesubvreint',
							'Traitementpdo.benefpriscompte',
							'Traitementpdo.revenus',
							'Traitementpdo.dtprisecompte',
						)
					);
					break;
				case 'reel':
				case 'ragri':
					echo $default2->view(
						$traitementpdo,
						array(
							'Traitementpdo.regime' => array( 'type' => 'text', 'value' => $regime ),
							'Traitementpdo.saisonnier' => array( 'type' => 'boolean' ),
							'Traitementpdo.nrmrcs',
							'Traitementpdo.dtdebutactivite',
							'Traitementpdo.raisonsocial',
							'Traitementpdo.dtdebutperiode',
							'Traitementpdo.dtfinperiode',
							'Traitementpdo.nbmoisactivite',
							'Traitementpdo.chaffvnt',
							'Traitementpdo.chaffsrv',
							'Traitementpdo.benefoudef',
							'Traitementpdo.ammortissements',
							'Traitementpdo.salaireexploitant',
							'Traitementpdo.provisionsnonded',
							'Traitementpdo.moinsvaluescession',
							'Traitementpdo.autrecorrection',
							// FIXME: calculs javascript
							'Traitementpdo.mnttotalpriscompte',
							'Traitementpdo.revenus',
							'Traitementpdo.dtprisecompte',
						)
					);
					break;
				case 'fagri':
					echo $default2->view(
						$traitementpdo,
						array(
							'Traitementpdo.regime' => array( 'type' => 'text', 'value' => $regime ),
							'Traitementpdo.saisonnier' => array( 'type' => 'boolean' ),
							'Traitementpdo.nrmrcs',
							'Traitementpdo.dtdebutactivite',
							'Traitementpdo.raisonsocial',
							'Traitementpdo.dtdebutperiode',
							'Traitementpdo.dtfinperiode',
							'Traitementpdo.nbmoisactivite',
							'Traitementpdo.forfait',
							'Traitementpdo.aidesubvreint' => array( 'type' => 'text', 'value' => Set::enum( $traitementpdo['Traitementpdo']['aidesubvreint'], $options['Traitementpdo']['aidesubvreint'] ) ),
							// FIXME: calculs javascript
							'Traitementpdo.mnttotalpriscompte',
							'Traitementpdo.revenus',
							'Traitementpdo.dtprisecompte',
						)
					);
					break;
			}
		}
	?>
</div>
<div class="clearer"><hr /></div>
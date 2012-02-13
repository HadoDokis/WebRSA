<?php
	$this->pageTitle =  __d( 'traitementpdo', "Traitementspdos::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Traitementpdo', array( 'type' => 'post', 'id' => 'traitementpdoform', 'url' => Router::url( null, true ) ) );
		echo $default2->view(
			$traitementpdo,
			array(
				'Descriptionpdo.name',
				'Traitementtypepdo.name',
				'Traitementpdo.datereception',
				'Traitementpdo.datedepart',
				'Traitementpdo.dureedepart' => array( 'type' => 'text', 'value' => @$options['Traitementpdo']['dureedepart'][$traitementpdo['Traitementpdo']['dureedepart']] ),
				'Traitementpdo.dateecheance',
				'Traitementpdo.dureefinperiode' => array( 'type' => 'text', 'value' => @$options['Traitementpdo']['dureefinperiode'][$traitementpdo['Traitementpdo']['dureefinperiode']] ),
				'Traitementpdo.daterevision',
				'Personne.nom_complet' => array( 'type' => 'string', 'value' => '#Personne.nom# #Personne.prenom#' )
			),
			array(
				'class' => 'aere'
			)
		);

		echo "<h2>Liste de courriers liés au traitement</h2>";
		if( !empty( $traitementpdo['Courrierpdo'] ) ){
			$courriersLies = Set::extract( $traitementpdo, 'Traitementpdo/Courrierpdo' );
			echo '<table><tbody>';
				echo '<tr><th>Intitulé du courrier</th><th>Action</th></tr>';
				if( isset( $courriersLies ) ){
					foreach( $courriersLies as $i => $courriers ){
						echo '<tr><td>'.$courriers['Courrierpdo']['name'].'</td>';
						echo '<td>'.$xhtml->link( 'Imprimer', array( 'action' => 'printCourrier', $courriers['Courrierpdo']['CourrierpdoTraitementpdo']['id']    ) ).'</td></tr>';
					}
				}
			echo '</tbody></table>';
		}
		else{
			echo '<p class="notice">Aucun élément.</p>';
		}

		echo "<h2>Pièces jointes</h2>";
		echo $fileuploader->results( Set::classicExtract( $traitementpdo, 'Fichiermodule' ) );


		echo '<h2>'.__d( 'traitementpdo', 'Traitementpdo.ficheanalyse', true ).'</h2>';
		if( empty( $traitementpdo['Traitementpdo']['ficheanalyse'] ) ) {
			echo '<p class="notice">Pas de fiche d\'analyse.</p>';
		}
		else {
			echo "<p>".nl2br( $traitementpdo['Traitementpdo']['ficheanalyse'] )."</p>";
		}

		echo '<h2>'.__d( 'traitementpdo', 'Traitementpdo.hasrevenu', true ).'</h2>';
		if( empty( $traitementpdo['Traitementpdo']['hasrevenu'] ) ) {
			echo '<p class="notice aere">Pas de revenu.</p>';
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
							'Traitementpdo.datefinperiode',
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
							'Traitementpdo.datefinperiode',
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
							'Traitementpdo.datefinperiode',
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
							'Traitementpdo.datefinperiode',
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
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>
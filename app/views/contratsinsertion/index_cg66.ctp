<?php
    $this->pageTitle = 'CER';
    $domain = 'contratinsertion';

    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
    <h1><?php  echo $this->pageTitle;?></h1>
        <?php if( empty( $orientstruct ) ) :?>
            <p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
        <?php else:?>
            <?php if( empty( $persreferent ) ) :?>
                <p class="error">Aucun référent n'est lié au parcours de cette personne.</p>
            <?php endif;?>

			<?php  if( !empty( $orientstructEmploi ) ) :?>
				<p class="error">Cette personne possède actuellement une orientation professionnelle. Impossible de créer un CER.</p>
			<?php endif; ?>


			<?php if( empty( $contratsinsertion ) ):?>
				<p class="notice">Cette personne ne possède pas encore de CER.</p>
			<?php endif;?>
			<ul class="actionMenu">
				<?php
					$block = empty( $orientstruct ) || !empty( $orientstructEmploi );

					echo '<li>'.$xhtml->addLink(
						'Ajouter un CER',
						array( 'controller' => 'contratsinsertion', 'action' => 'add', $personne_id ),
						( !$block )
					).' </li>';
				?>
			</ul>

	<?php if( !empty( $contratsinsertion ) ):?>

	<table class="tooltips default2">
		<thead>
			<tr>
				<th>Forme du contrat</th>
				<th>Type de contrat</th>
				<th>Date de début de contrat</th>
				<th>Date de fin de contrat</th>
				<th>Contrat signé le</th>
				<th>Décision</th>
				<th>Date décision</th>
				<th>Position du CER</th>
				<th colspan="12" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				foreach( $contratsinsertion as $contratinsertion ) {

					$action = ( ( $contratinsertion['Contratinsertion']['forme_ci'] == 'S' ) ? 'validersimple' : 'validerparticulier' );
					$dateCreation = Set::classicExtract( $contratinsertion, 'Contratinsertion.created' );
					$periodeblock = false;
					if( !empty( $dateCreation ) ){
						if(  ( mktime() >= ( strtotime( $dateCreation ) + 3600 * Configure::read( 'Periode.modifiablecer.nbheure' ) ) ) ){
							$periodeblock = true;
						}
					}

					$decision = Set::classicExtract( $decision_ci, Set::classicExtract( $contratinsertion, 'Contratinsertion.decision_ci' ) );
					$position = Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' );

					
					$datenotif = Set::classicExtract( $contratinsertion, 'Contratinsertion.datenotification' );
					if( empty( $datenotif ) ) {
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] );
					}
					else if( !empty( $datenotif ) && in_array( $position, array( 'nonvalidnotifie', 'validnotifie' ) ) ){
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] ).' le '.date_short( $datenotif );
					}
					else {
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] );
					}

					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $forme_ci, Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ) ) ),
							h( Set::classicExtract( $options['num_contrat'], Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.date_saisi_ci' ) ) ),
							h( $decision ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.datedecision' ) ) ),
							h( $positioncer ),

							$default2->button(
								'valider',
								array( 'controller' => 'contratsinsertion', 'action' => $action,
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
											( $permissions->check( 'contratsinsertion', $action ) == 1 )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'fincontrat' )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
									)
								)
							),
							$default2->button(
								'view',
								array( 'controller' => 'contratsinsertion', 'action' => 'view',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'view' ) == 1
									)
								)
							),
							$default2->button(
								'edit',
								array( 'controller' => 'contratsinsertion', 'action' => 'edit',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'edit' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& ( !$periodeblock )
									)
								)
							),
							$default2->button(
								'proposition',
								array( 'controller' => 'proposdecisionscers66', 'action' => 'proposition',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'proposdecisionscers66', 'proposition' ) == 1 )
									)
								)
							),
							$default2->button(
								'notification',
								array( 'controller' => 'contratsinsertion', 'action' => 'notification',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'notification' )
									)
								)
							),
							$default2->button(
								'ficheliaisoncer',
								array( 'controller' => 'contratsinsertion', 'action' => 'ficheliaisoncer',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'ficheliaisoncer' )
									)
								)
							),
							$default2->button(
								'notifbenef',
								array( 'controller' => 'contratsinsertion', 'action' => 'notifbenef',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'notifbenef' )
									)
								)
							),
							$default2->button(
								'notifop',
								array( 'controller' => 'contratsinsertion', 'action' => 'notificationsop',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'notificationsop' ) == 1 )
									)
								)
							),
							$default2->button(
								'print',
								array( 'controller' => 'contratsinsertion', 'action' => 'impression',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'impression' ) == 1 )
									)
								)
							),
							$default2->button(
								'cancel',
								array( 'controller' => 'contratsinsertion', 'action' => 'cancel',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'cancel' ) == 1 )
									)
								)
							),
							$default2->button(
								'filelink',
								array( 'controller' => 'contratsinsertion', 'action' => 'filelink',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'filelink' ) == 1
									)
								)
							),
							h( '('.Set::classicExtract( $contratinsertion, 'Fichiermodule.nbFichiersLies' ).')' )
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
			?>
		</tbody>
	</table>
	<?php  endif;?>
<?php endif;?>
</div>
<div class="clearer"><hr /></div>

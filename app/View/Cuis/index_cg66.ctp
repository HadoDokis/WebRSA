<?php
	$this->pageTitle = __d( 'cui', "Cuis::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<?php echo $this->Xhtml->tag( 'h1', $this->pageTitle );?>

	<?php if( $this->Permissions->check( 'cuis', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Ajouter un CUI',
					array( 'controller' => 'cuis', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php endif;?>
	<?php if( !empty( $alerteRsaSocle ) ):?>
		<p class="error">Cette personne ne possède pas de rSA Socle.</p>
	<?php endif;?>
	
	<?php if( empty( $cuis ) ):?>
		<p class="notice">Cette personne ne possède pas encore de CUI.</p>
	<?php endif;?>

	<?php if( !empty( $cuis ) ):?>
	<table class="tooltips default2" id="searchResults">
		<thead>
			<tr>
				<th>Date du contrat</th>
				<th>Secteur</th>
				<th>Employeur</th>
				<th>Date de début de prise en charge</th>
				<th>Date de fin de prise en charge</th>
				<th>Position CUI</th>
				<th>Décision pour le CUI</th>
				<th>Date de validation</th>
				<th colspan="10" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $cuis as $index => $cui ):?>
				<?php

                    $innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Raison annulation</th>
									<td>'.$cui['Cui']['motifannulation'].'</td>
								</tr>
							</tbody>
						</table>';
                    
					echo $this->Xhtml->tableCells(
						array(
							h( date_short( Set::classicExtract( $cui, 'Cui.datecontrat' ) ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.secteur' ), $options['Cui']['secteur'] ) ),
							h( Set::classicExtract( $cui, 'Cui.nomemployeur' ) ),
							h( date_short( Set::classicExtract( $cui, 'Cui.datedebprisecharge' ) ) ),
							h( date_short( Set::classicExtract( $cui, 'Cui.datefinprisecharge' ) ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.positioncui66' ), $options['Cui']['positioncui66'] ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.decisioncui' ), $options['Cui']['decisioncui'] ) ),
							h( date_short( Set::classicExtract( $cui, 'Cui.datevalidationcui' ) ) ),
// 							$this->Default2->button(
// 								'view',
// 								array( 'controller' => 'cuis', 'action' => 'view', $cui['Cui']['id'] ),
// 								array(
// 									'enabled' => (
// 										( $this->Permissions->check( 'cuis', 'view' ) == 1 )
// 									)
// 								)
// 							),
							$this->Default2->button(
								'edit',
								array( 'controller' => 'cuis', 'action' => 'edit', $cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $this->Permissions->check( 'cuis', 'edit' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'proposition',
								array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
                                        ( $this->Permissions->check( 'proposdecisionscuis66', 'propositioncui' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'valider',
								array( 'controller' => 'decisionscuis66', 'action' => 'decisioncui',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $this->Permissions->check( 'cuis', 'decisioncui' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'print',
								array( 'controller' => 'cuis', 'action' => 'impression',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $this->Permissions->check( 'cuis', 'impression' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'suspension',
								array( 'controller' => 'suspensionscuis66', 'action' => 'index',
								$cui['Cui']['id'] ),
								array(
									'label' => 'Suspension/Rupture',
									'enabled' => (
										( $this->Permissions->check( 'suspensionscuis66', 'index' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'accompagnement',
								array( 'controller' => 'accompagnementscuis66', 'action' => 'index',
								$cui['Cui']['id'] ),
								array(
									'label' => 'Accompagnement',
									'enabled' => (
										( $this->Permissions->check( 'accompagnementscuis66', 'index' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'cancel',
								array( 'controller' => 'cuis', 'action' => 'cancel',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $this->Permissions->check( 'cuis', 'cancel' ) == 1 )
                                        && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									)
								)
							),
							$this->Default2->button(
								'filelink',
								array( 'controller' => 'cuis', 'action' => 'filelink',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $this->Permissions->check( 'cuis', 'filelink' ) == 1 )
									)
								)
							),
							h( '('.Set::classicExtract( $cui, 'Fichiermodule.nb_fichiers_lies' ).')' ),
                            array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
						),
						array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
						array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
					);
				?>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>
<?php
	$this->pageTitle = __d( 'cui', "Cuis::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<?php echo $this->Xhtml->tag( 'h1', $this->pageTitle );?>

<?php if( $this->Permissions->checkDossier( 'cuis', 'add', $dossierMenu ) ):?>
	<?php if( !empty( $secteurscuis ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Ajouter un CUI',
					array( 'controller' => 'cuis', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php else :?>
		<p class="error">Veuillez paramétrer les différents types de secteurs de CUI afin de pouvoir créer un nouveau CUI.</p>
	<?php endif;?>
<?php endif;?>
<?php if( empty( $cuis ) ):?>
	<p class="notice">Cette personne ne possède pas encore de CUI.</p>
<?php endif;?>


<?php if( !empty( $cuis ) ):?>
<table class="tooltips default2">
	<thead>
		<tr>
			<th>Date du contrat</th>
			<th>Secteur</th>
			<th>Employeur</th>
			<th>Décision pour le CUI</th>
			<th>Date de validation</th>
			<th colspan="7" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $cuis as $cui ):?>
			<?php
				echo $this->Xhtml->tableCells(
					array(
						h( date_short( Set::classicExtract( $cui, 'Cui.datecontrat' ) ) ),
						h( Set::classicExtract( $cui, 'Secteurcui.name' ) ),
						h( Set::classicExtract( $cui, 'Cui.nomemployeur' ) ),
						h( Set::enum( Set::classicExtract( $cui, 'Cui.decisioncui' ), $options['Cui']['decisioncui'] ) ),
						h( date_short( Set::classicExtract( $cui, 'Cui.datevalidationcui' ) ) ),
// 							$this->Default2->button(
// 								'view',
// 								array( 'controller' => 'cuis', 'action' => 'view', $cui['Cui']['id'] ),
// 								array(
// 									'enabled' => (
// 										$this->Permissions->checkDossier( 'cuis', 'view', $dossierMenu ) == 1
// 									)
// 								)
// 							),
						$this->Default2->button(
							'edit',
							array( 'controller' => 'cuis', 'action' => 'edit', $cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'edit', $dossierMenu ) == 1 )
								)
							)
						),
						$this->Default2->button(
							'valider',
							array( 'controller' => 'cuis', 'action' => 'valider',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'valider', $dossierMenu ) == 1 )
								)
							)
						),
						$this->Default2->button(
							'print',
							array( 'controller' => 'cuis', 'action' => 'impression',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'impression', $dossierMenu ) == 1 )
								)
							)
						),
						$this->Default2->button(
							'cancel',
							array( 'controller' => 'cuis', 'action' => 'cancel',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'cancel', $dossierMenu ) == 1 )
								)
							)
						),
						$this->Default2->button(
							'filelink',
							array( 'controller' => 'cuis', 'action' => 'filelink',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									$this->Permissions->checkDossier( 'cuis', 'filelink', $dossierMenu ) == 1
								)
							)
						),
						h( '('.Set::classicExtract( $cui, 'Fichiermodule.nb_fichiers_lies' ).')' )
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			?>
		<?php endforeach;?>
	</tbody>
</table>
<?php  endif;?>
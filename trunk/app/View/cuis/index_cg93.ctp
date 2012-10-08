<?php
	$this->pageTitle = __d( 'cui', "Cuis::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<?php echo $xhtml->tag( 'h1', $this->pageTitle );?>

	<?php if( $permissions->check( 'cuis', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter un CUI',
					array( 'controller' => 'cuis', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
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
				<th>Dénomination</th>
				<th>Décision pour le CUI</th>
				<th>Date de validation</th>
				<th colspan="7" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $cuis as $cui ):?>
				<?php
					echo $xhtml->tableCells(
						array(
							h( date_short( Set::classicExtract( $cui, 'Cui.datecontrat' ) ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.secteur' ), $options['secteur'] ) ),
							h( Set::classicExtract( $cui, 'Cui.nomemployeur' ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.decisioncui' ), $options['decisioncui'] ) ),
							h( date_short( Set::classicExtract( $cui, 'Cui.datevalidationcui' ) ) ),
							$default2->button(
								'view',
								array( 'controller' => 'cuis', 'action' => 'view', $cui['Cui']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'cuis', 'view' ) == 1
									)
								)
							),
							$default2->button(
								'edit',
								array( 'controller' => 'cuis', 'action' => 'edit', $cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'cuis', 'edit' ) == 1 )
									)
								)
							),
							$default2->button(
								'valider',
								array( 'controller' => 'cuis', 'action' => 'valider',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'cuis', 'valider' ) == 1 )
									)
								)
							),
							$default2->button(
								'print',
								array( 'controller' => 'cuis', 'action' => 'impression',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'cuis', 'impression' ) == 1 )
									)
								)
							),
							$default2->button(
								'cancel',
								array( 'controller' => 'cuis', 'action' => 'cancel',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'cuis', 'cancel' ) == 1 )
									)
								)
							),
							$default2->button(
								'filelink',
								array( 'controller' => 'cuis', 'action' => 'filelink',
								$cui['Cui']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'cuis', 'filelink' ) == 1
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
</div>
<div class="clearer"><hr /></div>
<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
	);
?>
<?php
	if( isset( $actionscandidats ) ) {
		$pagination = $xpaginator->paginationBlock( 'Actioncandidat', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>
<?php if( empty( $actionscandidats ) ):?>
	<p class="notice">Aucune action présente</p>
<?php endif;?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter une action',
				array( 'controller' => 'actionscandidats', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<?php if( !empty( $actionscandidats ) ):?>
	<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Intitulé de l\'action', 'Actioncandidat.name' );?></th>
					<th><?php echo $xpaginator->sort( 'Code de l\'action', 'Actioncandidat.codeaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Active', 'Actioncandidat.actif' );?></th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $actionscandidats as $actioncandidat ){
// debug($actioncandidat);
						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.name' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.codeaction' ) ),
								h( Set::enum( Set::classicExtract( $actioncandidat, 'Actioncandidat.actif' ), $options['Actioncandidat']['actif'] ) ),

								$xhtml->editLink(
									'Editer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'edit',
									$actioncandidat['Actioncandidat']['id'] ),
									( $permissions->check( 'actionscandidats', 'edit' ) == 1 )
								),
								$xhtml->deleteLink(
									'Supprimer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'delete',
									$actioncandidat['Actioncandidat']['id'] ),
									( $permissions->check( 'actionscandidats', 'delete' ) == 1 )
								)
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					}

				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
	<?php  endif;?>

	
	
<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'actionscandidats_personnes',
			'action'     => 'indexparams'
		),
		array(
			'id' => 'Back'
		)
	);
?>

<?php
	$title_for_layout = 'Contrats d\'engagement réciproques';
	$this->set( 'title_for_layout', $title_for_layout );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<?php echo $this->Html->tag( 'h1', $title_for_layout );?>

	<ul class="actionMenu">
		<li><?php echo $this->Xhtml->addLink( 'Ajouter', array( 'action' => 'add', $personne_id ) );?></li>
	</ul>

	<?php
		echo $this->Default2->index(
			$cers93,
			array(
				'Contratinsertion.id',
				'Contratinsertion.dd_ci',
				'Contratinsertion.df_ci',
				'Contratinsertion.decision_ci',
			),
			array(
				'actions' => array(
					'Cers93::view' => array( 'url' => array( 'action' => 'view', '#Contratinsertion.id#' ) ),
					'Cers93::edit' => array( 'url' => array( 'action' => 'edit', '#Contratinsertion.id#' ) ),
				)
			)
		);
		debug( $cers93 );
	?>
</div>
<div class="clearer"><hr /></div>
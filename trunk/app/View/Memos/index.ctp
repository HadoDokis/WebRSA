<?php  $this->pageTitle = 'Mémos concernant la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1>Mémos</h1>
	<ul class="actionMenu">
		<li><?php
				echo $this->Xhtml->addLink(
					'Ajouter',
					array(
						'action' => 'add',
						$personne_id
					)
				);
			?>
		</li>
	</ul>
	<?php
		echo $this->Default2->index(
			$memos,
			array(
				'Memo.name',
				'Memo.created',
				'Memo.modified',
				'Memo.nb_fichiers_lies' => array( 'type' => 'integer' )
			),
			array(
				'cohorte' => false,
				'actions' => array(
					'Memos::edit',
					'Memos::delete',
					'Memos::filelink'
				)
			)
		)
	?>
</div>
<div class="clearer"><hr /></div>
<?php  $this->pageTitle = 'Mémos concernant la personne';?>

<h1>Mémos</h1>
<?php echo $this->element( 'ancien_dossier' );?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter',
				array(
					'action' => 'add',
					$personne_id
				),
				$this->Permissions->checkDossier( 'memos', 'add', $dossierMenu )
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
				'Memos::edit' => array(
					'disabled' => !$this->Permissions->checkDossier( 'memos', 'edit', $dossierMenu )
				),
				'Memos::delete' => array(
					'disabled' => !$this->Permissions->checkDossier( 'memos', 'delete', $dossierMenu )
				),
				'Memos::filelink' => array(
					'disabled' => !$this->Permissions->checkDossier( 'memos', 'filelink', $dossierMenu )
				)
			)
		)
	)
?>
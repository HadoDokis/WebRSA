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
	// Formatage des textes de mémos pour la liste
	if( !empty( $memos ) ) {
		$names = Hash::extract( $memos, '{n}.Memo.name' );

		foreach( $names as $key => $value ) {
			$value = String::truncate( $value, 250 );
			$value = nl2br( $value );

			$memos[$key]['Memo']['name'] = $value;
		}
	}

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
				'Memos::view' => array(
					'disabled' => !$this->Permissions->checkDossier( 'memos', 'view', $dossierMenu )
				),
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
<?php
    $domain = 'modecontact';
?>

<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( $domain, "Modescontact::{$this->action}" )
	);
?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter',
				array(
					'action' => 'add',
					$foyer_id
				),
				$this->Permissions->checkDossier( 'modescontact', 'add', $dossierMenu )
			);
		?>
	</li>
</ul>
<?php
	echo $this->Default2->index(
		$modescontact,
		array(
			'Modecontact.numtel',
			'Modecontact.numposte',
			'Modecontact.nattel',
			'Modecontact.matetel',
			'Modecontact.autorutitel',
			'Modecontact.adrelec',
			'Modecontact.autorutiadrelec'
		),
		array(
			'actions' => array(
				'Modescontact::view' => array(
					'domain' => $domain,
					'disabled' =>  '( "'.$this->Permissions->checkDossier( 'modescontact', 'view', $dossierMenu ).'" != "1" )'
				),
				'Modescontact::edit' => array(
					'domain' => $domain,
					'disabled' =>  '( "'.$this->Permissions->checkDossier( 'modescontact', 'edit', $dossierMenu ).'" != "1" )'
				)
			),
			'options' => $options,
		)
	);
?>
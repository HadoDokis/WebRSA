<?php
	$this->pageTitle = 'Accompagnement';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'accompagnementcui66', "Accompagnementscuis66::{$this->action}" )
	);
?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter un accompagnement',
				array( 'controller'=> 'accompagnementscuis66', 'action'=>'add', $cui_id ),
				$this->Permissions->checkDossier( 'accompagnementscuis66', 'add', $dossierMenu )
			);
		?>
	</li>
</ul>
<?php
	echo $this->Default2->index(
		$accompagnementscuis66,
		array(
			'Accompagnementcui66.typeaccompagnementcui66',
			'Accompagnementcui66.datedebperiode',
			'Accompagnementcui66.datefinperiode',
			'Accompagnementcui66.nomentaccueil',
			'Accompagnementcui66.objectifimmersion',
			'Accompagnementcui66.datesignatureimmersion',
			'Fichiermodule.nb_fichiers_lies' => array( 'label' => 'Nb de fichiers liés', 'type' => 'text' )
		),
		array(
			'actions' => array(
				'Accompagnementscuis66::edit' => array(
					'disabled' => !$this->Permissions->checkDossier( 'accompagnementscuis66', 'edit', $dossierMenu )
				),
				'Accompagnementscuis66::impression' => array(
					'disabled' => !$this->Permissions->checkDossier( 'accompagnementscuis66', 'impression', $dossierMenu )
				),
				'Accompagnementscuis66::delete' => array(
					'disabled' => !$this->Permissions->checkDossier( 'accompagnementscuis66', 'delete', $dossierMenu )
				),
				'Accompagnementscuis66::filelink' => array(
					'disabled' => !$this->Permissions->checkDossier( 'accompagnementscuis66', 'filelink', $dossierMenu )
				)
			),
			'options' => $options
		)
	);
?>
<div>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'cuis',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back'
		)
	);
?>
</div>
<?php
	$this->pageTitle = 'Suspension/Rupture';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'suspensioncui66', "Suspensionscuis66::{$this->action}" )
	);
?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter une suspension',
				array( 'controller'=> $this->request->params['controller'], 'action'=>'add', $cui_id ),
				false && $this->Permissions->checkDossier( $this->request->params['controller'], 'add', $dossierMenu )
			);
		?>
	</li>
</ul>
<?php

// 		$listeoptions = $options;
// 		unset( $options );
// 		$options['Suspensioncui66'] = $listeoptions;

	echo $this->Default2->index(
		$suspensionscuis66,
		array(
			'Suspensioncui66.datedebperiode',
			'Suspensioncui66.datefinperiode',
			'Suspensioncui66.nomentaccueil',
			'Suspensioncui66.objectifimmersion',
			'Suspensioncui66.datesignatureimmersion'
		),
		array(
			'actions' => array(
				'Suspensionscuis66::edit' => array(
					'disabled' => !$this->Permissions->checkDossier( $this->request->params['controller'], 'edit', $dossierMenu )
				),
				'Suspensionscuis66::delete' => array(
					'disabled' => !$this->Permissions->checkDossier( $this->request->params['controller'], 'edit', $dossierMenu )
				)
			),
			'options' => $options
		)
	);
?>
</div>
<?php echo $this->Xform->create( 'Suspensioncui66' );?>
<div class="submit">
	<?php echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) ); ?>
</div>
<?php echo $this->Xform->end(); ?>
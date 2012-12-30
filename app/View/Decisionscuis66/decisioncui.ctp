<?php
	$this->pageTitle = __d( 'decisioncui66', "Decisionscuis66::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
?>
<?php if( $this->Permissions->checkDossier( 'decisionscuis66', 'add', $dossierMenu ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter une décision',
				array( 'controller' => 'decisionscuis66', 'action' => 'add', $cui_id )
			).' </li>';
		?>
	</ul>
<?php endif;?>
<?php
	echo $this->Default2->index(
		$decisionscuis66,
		array(
			'Decisioncui66.decisioncui',
			'Decisioncui66.datedecisioncui',
			'Decisioncui66.observdecisioncui'
		),
		array(
			'actions' => array(
				'Decisionscuis66::edit' => array(
					'disabled' => !$this->Permissions->checkDossier( 'decisionscuis66', 'edit', $dossierMenu )
				),
				'Decisionscuis66::notifelu' => array(
					'label' => 'Décision élu',
					'url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#', 'elu' ),
					'disabled' => !$this->Permissions->checkDossier( 'decisionscuis66', 'impression', $dossierMenu )
				),
				'Decisionscuis66::notifbenef' => array(
					'label' => 'Notification bénéficiaire',
					'url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#/', 'benef' ),
					'disabled' => !$this->Permissions->checkDossier( 'decisionscuis66', 'impression', $dossierMenu )
				),
				'Decisionscuis66::notifemployeur' => array(
					'label' => 'Notification employeur',
					'url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#', 'employeur' ),
					'disabled' => !$this->Permissions->checkDossier( 'decisionscuis66', 'impression', $dossierMenu )
				),
				'Decisionscuis66::delete' => array(
					'disabled' => !$this->Permissions->checkDossier( 'decisionscuis66', 'delete', $dossierMenu )
				)
			),
			'options' => $options
		)
	);
?>
<?php echo $this->Xform->create( 'Decisioncui66' );?>
<div class="submit">
	<?php
		echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>
<?php echo $this->Xform->end(); ?>
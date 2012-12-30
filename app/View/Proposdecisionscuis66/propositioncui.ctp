<?php
	$this->pageTitle = 'Avis techniques';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter un avis',
				array( 'controller'=>'proposdecisionscuis66', 'action'=>'add', $cui_id ),
				$this->Permissions->checkDossier( $this->request->params['controller'], 'add', $dossierMenu )
			);
		?>
	</li>
</ul>
<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'propodecisioncui66', "Proposdecisionscuis66::{$this->action}" )
	);

	echo $this->Default2->index(
		$proposdecisionscuis66,
		array(
			'Propodecisioncui66.propositioncui',
			'Propodecisioncui66.datepropositioncui',
			'Propodecisioncui66.observcui',
			'Propodecisioncui66.propositioncuielu',
			'Propodecisioncui66.datepropositioncuielu',
			'Propodecisioncui66.observcuielu',
			'Propodecisioncui66.propositioncuireferent',
			'Propodecisioncui66.datepropositioncuireferent',
			'Propodecisioncui66.observcuireferent'
		),
		array(
			'actions' => array(
				'Proposdecisionscuis66::edit' => array(
					'disabled' => !$this->Permissions->checkDossier( 'proposdecisionscuis66', 'edit', $dossierMenu )
				),
				'Proposdecisionscuis66::notifelucui' => array(
					'disabled' => !$this->Permissions->checkDossier( 'proposdecisionscuis66', 'notifelucui', $dossierMenu )
				),
				'Proposdecisionscuis66::delete' => array(
					'disabled' => !$this->Permissions->checkDossier( 'proposdecisionscuis66', 'delete', $dossierMenu )
				)
			),
			'options' => $options
		)
	);
?>

<?php echo $this->Xform->create( 'Propodecisioncui66' );?>
<div class="submit">
	<?php
		echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>
<?php echo $this->Xform->end(); ?>

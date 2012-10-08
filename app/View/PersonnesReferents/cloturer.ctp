<?php
	$this->pageTitle = 'Clôture du référent';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php echo $this->Form->create( 'PersonneReferent',array( 'url' => Router::url( null, true ) ) );?>
		<fieldset>
			<?php

				echo $this->Default2->subform(
					array(
						'PersonneReferent.id' => array( 'type' => 'hidden' ),
						'PersonneReferent.personne_id' => array( 'type' => 'hidden' ),
						'PersonneReferent.structurereferente_id' => array( 'disabled' => true, 'value' => $personne_referent['PersonneReferent']['structurereferente_id'] ),
						'PersonneReferent.referent_id' => array( 'disabled' => true, 'value' => $personne_referent['PersonneReferent']['referent_id'] ),
						'PersonneReferent.dddesignation' => array( 'disabled' => true, 'value' => $personne_referent['PersonneReferent']['dddesignation'] )
					),
					array(
						'options' => $options,
						'domain' => 'personne_referent'
					)
				);

				echo $this->Default2->subform(
					array(
						'PersonneReferent.dddesignation' => array( 'type' => 'hidden' ),//Champ nécessaire pour la comparaison de date, sinon n'apparait pas dans $this->request->data
						'PersonneReferent.dfdesignation'
					),
					array(
						'options' => $options,
						'domain' => 'personne_referent'
					)
				);
			?>
		</fieldset>

	<div class="submit">
		<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $this->Form->end();?>
</div>

<div class="clearer"><hr /></div>
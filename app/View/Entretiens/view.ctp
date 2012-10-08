<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}" )
		);
	?>
	<?php
		echo $this->Default->view(
			$entretien,
			array(
				'Entretien.dateentretien',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Entretien.typeentretien',
				'Entretien.typerdv_id',
				'Entretien.commentaireentretien'
			),
			array(
				'options' => $options
			)
		);
		    
		echo $this->Default->button(
			'back',
			array(
				'controller' => 'entretiens',
				'action'     => 'index',
				$personne_id
			),
			array(
				'id' => 'Back'
			)
		);
	?>
	
</div>
<div class="clearer"><hr /></div>
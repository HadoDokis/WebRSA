<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}", true )
		);
	?>
	<?php
		echo $default->view(
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
		    
		echo $default->button(
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
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typeaideapre66', "Typesaidesapres66::{$this->action}" )
	);

	echo $this->Xform->create();

	echo $this->Default->subform(
		array(
			'Typeaideapre66.id' => array( 'type' => 'hidden' ),
			'Typeaideapre66.themeapre66_id',
			'Typeaideapre66.name',
            'Typeaideapre66.isincohorte' => array( 'type' => 'radio'),
			'Typeaideapre66.objetaide' => array( 'type' => 'text' ),
			'Typeaideapre66.plafond' => array( 'type' => 'text' ),
			'Nameapre66.Nameapre66' => array(
				'label' => 'Type d\'aide', 'multiple' => 'checkbox' , 'options' => $namesapres, 'empty' => false
			),
			'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces administratives', 'multiple' => 'checkbox' , 'options' => $pieceadmin, 'empty' => false ),
		),
		array(
			'options' => $options
		)
	);
?>
<div>
<script type="text/javascript">
	function toutCocherPieceaide66() {
		return toutCocher( 'input[name="data[Pieceaide66][Pieceaide66][]"]' );
	}
	function toutDecocherPieceaide66() {
		return toutDecocher( 'input[name="data[Pieceaide66][Pieceaide66][]"]' );
	}
</script>
<?php
	echo $this->Form->button( 'Tout cocher', array( 'type' => 'button', 'onclick' => "return toutCocherPieceaide66();" ) );
	echo $this->Form->button( 'Tout décocher', array( 'type' => 'button', 'onclick' => "return toutDecocherPieceaide66();" ) );
?>
</div>
<?php

	echo $this->Default->subform(
		array(
			'Piececomptable66.Piececomptable66' => array( 'label' => 'Pièces comptables', 'multiple' => 'checkbox' , 'options' => $piececomptable, 'empty' => false )
		),
		array(
			'options' => $options
		)
	);
?>
<div>
<script type="text/javascript">
	function toutCocherPiececomptable66() {
		return toutCocher( 'input[name="data[Piececomptable66][Piececomptable66][]"]' );
	}
	function toutDecocherPiececomptable66() {
		return toutDecocher( 'input[name="data[Piececomptable66][Piececomptable66][]"]' );
	}
</script>
<?php
	echo $this->Form->button( 'Tout cocher', array( 'type' => 'button', 'onclick' => "return toutCocherPiececomptable66();" ) );
	echo $this->Form->button( 'Tout décocher', array( 'type' => 'button', 'onclick' => "return toutDecocherPiececomptable66();" ) );
?>
</div>

<?php echo $this->Xform->end( 'Save' ); ?>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'typesaidesapres66',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
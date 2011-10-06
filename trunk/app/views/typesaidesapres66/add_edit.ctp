<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typeaideapre66', "Typesaidesapres66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default->subform(
		array(
			'Typeaideapre66.id' => array( 'type' => 'hidden' ),
			'Typeaideapre66.themeapre66_id',
			'Typeaideapre66.name',
			'Typeaideapre66.objetaide' => array( 'type' => 'text' ),
			'Typeaideapre66.plafond' => array( 'type' => 'text' ),
			'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces administratives', 'multiple' => 'checkbox' , 'options' => $pieceadmin, 'empty' => false ),
		),
		array(
			'options' => $options
		)
	);
?>
<div>
<?php
	echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Pieceaide66][Pieceaide66][]\"]' )" ) );
	echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Pieceaide66][Pieceaide66][]\"]' )" ) );
?>
</div>
<?php

	echo $default->subform(
		array(
			'Piececomptable66.Piececomptable66' => array( 'label' => 'Pièces comptables', 'multiple' => 'checkbox' , 'options' => $piececomptable, 'empty' => false )
		),
		array(
			'options' => $options
		)
	);
?>
<div>
<?php
	echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Piececomptable66][Piececomptable66][]\"]' )" ) );
	echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Piececomptable66][Piececomptable66][]\"]' )" ) );
?>
</div>

<?php echo $xform->end( 'Save' ); ?>
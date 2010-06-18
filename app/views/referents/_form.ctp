<fieldset>
	<?php
		echo $default->subform(
			array(
				'Referent.qual' => array( 'options' => $qual ),
				'Referent.nom',
				'Referent.prenom',
				'Referent.fonction',
				'Referent.numero_poste' => array( 'maxlength' => 10 ),
				'Referent.email',
			)
		);
	?>
    <?php
		/*echo $form->input( 'Referent.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );
		echo $form->input( 'Referent.nom', array( 'label' => required( __( 'nom', true ) ), 'type' => 'text' ) );
		echo $form->input( 'Referent.prenom', array( 'label' => required( __( 'prenom', true ) ), 'type' => 'text' ) );
		echo $form->input( 'Referent.fonction', array( 'label' => required( __( 'fonction', true ) ), 'type' => 'text' ) );
		echo $form->input( 'Referent.numero_poste', array( 'label' => required( __( 'numero_poste', true ) ), 'type' => 'text', 'maxlength' => 10 ) );
		echo $form->input( 'Referent.email', array( 'label' => required( __( 'email', true ) ), 'type' => 'text' ) );*/
	?>
</fieldset>
<fieldset class="col2">
    <legend>Structures référentes</legend>
    <?php echo $form->input( 'Referent.structurereferente_id', array( 'label' => required( false ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
</fieldset>
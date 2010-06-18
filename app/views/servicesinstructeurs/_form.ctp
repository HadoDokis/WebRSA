<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //observeDisableFieldsOnValue( 'ServiceinstructeurNumdepins', [ 'ServiceinstructeurTypeserins' ], 093, false );
    });
</script>

<fieldset>
	<?php
		echo $default->subform(
			array(
				'Serviceinstructeur.lib_service' => array( 'label' => required( __( 'lib_service', true ) ) ),
				'Serviceinstructeur.num_rue' => array( 'label' =>  __( 'num_rue', true ) ),
				'Serviceinstructeur.type_voie' => array( 'label' =>  required( __( 'type_voie', true ) ), 'options' => $typevoie ),
				'Serviceinstructeur.nom_rue' => array( 'label' =>  __( 'nom_rue', true ) ),
				'Serviceinstructeur.complement_adr' => array( 'label' =>  __( 'complement_adr', true ) ),
				'Serviceinstructeur.code_insee' => array( 'label' =>  required( __( 'code_insee', true ) ) ),
				'Serviceinstructeur.code_postal' => array( 'label' =>  __( 'code_postal', true ) ),
				'Serviceinstructeur.ville' => array( 'label' =>  __( 'ville', true ) ),
			)
		);
	?>
    <?php
		/*echo $form->input( 'Serviceinstructeur.lib_service', array( 'label' =>  required( __( 'lib_service', true ) ), 'type' => 'text', 'maxlength' => 100 ) );
		echo $form->input( 'Serviceinstructeur.num_rue', array( 'label' =>  __( 'num_rue', true ), 'type' => 'text', 'maxlength' => 15 ) );
		echo $form->input( 'Serviceinstructeur.type_voie', array( 'label' =>  required(  __( 'type_voie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );
		echo $form->input( 'Serviceinstructeur.nom_rue', array( 'label' =>  __( 'nom_rue', true ), 'type' => 'text', 'maxlength' => 100  ) );
		echo $form->input( 'Serviceinstructeur.complement_adr', array( 'label' =>  __( 'complement_adr', true ), 'type' => 'text' ) );
		echo $form->input( 'Serviceinstructeur.code_insee', array( 'label' =>  required( __( 'code_insee', true ) ), 'type' => 'text' ) );
		echo $form->input( 'Serviceinstructeur.code_postal', array( 'label' =>  __( 'code_postal', true ), 'type' => 'text', 'maxlength' => 5 ) );
		echo $form->input( 'Serviceinstructeur.ville', array( 'label' =>  __( 'ville', true ), 'type' => 'text' ) );*/
	?>
</fieldset>
<fieldset>
    <?php
		echo $default->subform(
			array(
				'Serviceinstructeur.numdepins' => array( 'label' => required( __( 'numdepins', true ) ) ),
				'Serviceinstructeur.typeserins' => array( 'label' => required( __( 'typeserins', true ) ), 'empty' => true ),
				'Serviceinstructeur.numcomins' => array( 'label' => required( __( 'numcomins', true ) ) ),
				'Serviceinstructeur.numagrins' => array( 'label' => required( __( 'numagrins', true ) ), 'maxlength' => 2 ),
			)
		);
		/*echo '<hr/>';
		echo $form->input( 'Serviceinstructeur.numdepins', array( 'label' =>  required( __( 'numdepins', true ) ), 'type' => 'text', 'maxlength' => 3 ) );
		echo $form->input( 'Serviceinstructeur.typeserins', array( 'label' => required( __( 'typeserins', true ) ), 'type' => 'select', 'empty' => true ) );
		echo $form->input( 'Serviceinstructeur.numcomins', array( 'label' =>  required( __( 'numcomins', true ) ), 'type' => 'text', 'maxlength' => 3 ) );
		echo $form->input( 'Serviceinstructeur.numagrins', array( 'label' => required( __( 'numagrins', true ) ), 'type' => 'text', 'maxlength' => 2 ) );*/
	?>
</fieldset>

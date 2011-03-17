<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //observeDisableFieldsOnValue( 'ServiceinstructeurNumdepins', [ 'ServiceinstructeurTypeserins' ], 093, false );
    });
</script>

<fieldset>
	<?php
		echo $default->subform(
			array(
				'Serviceinstructeur.lib_service' => array( 'label' => ( __( 'lib_service', true ) ) ),
				'Serviceinstructeur.num_rue' => array( 'label' =>  __( 'num_rue', true ) ),
				'Serviceinstructeur.type_voie' => array( 'label' =>  ( __( 'type_voie', true ) ), 'options' => $typevoie ),
				'Serviceinstructeur.nom_rue' => array( 'label' =>  __( 'nom_rue', true ) ),
				'Serviceinstructeur.complement_adr' => array( 'label' =>  __( 'complement_adr', true ) ),
				'Serviceinstructeur.code_insee' => array( 'label' =>  ( __( 'code_insee', true ) ) ),
				'Serviceinstructeur.code_postal' => array( 'label' =>  __( 'code_postal', true ) ),
				'Serviceinstructeur.ville' => array( 'label' =>  __( 'ville', true ) ),
			)
		);
	?>
    <?php
		/*echo $form->input( 'Serviceinstructeur.lib_service', array( 'label' =>  ( __( 'lib_service', true ) ), 'type' => 'text', 'maxlength' => 100 ) );
		echo $form->input( 'Serviceinstructeur.num_rue', array( 'label' =>  __( 'num_rue', true ), 'type' => 'text', 'maxlength' => 15 ) );
		echo $form->input( 'Serviceinstructeur.type_voie', array( 'label' =>  (  __( 'type_voie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );
		echo $form->input( 'Serviceinstructeur.nom_rue', array( 'label' =>  __( 'nom_rue', true ), 'type' => 'text', 'maxlength' => 100  ) );
		echo $form->input( 'Serviceinstructeur.complement_adr', array( 'label' =>  __( 'complement_adr', true ), 'type' => 'text' ) );
		echo $form->input( 'Serviceinstructeur.code_insee', array( 'label' =>  ( __( 'code_insee', true ) ), 'type' => 'text' ) );
		echo $form->input( 'Serviceinstructeur.code_postal', array( 'label' =>  __( 'code_postal', true ), 'type' => 'text', 'maxlength' => 5 ) );
		echo $form->input( 'Serviceinstructeur.ville', array( 'label' =>  __( 'ville', true ), 'type' => 'text' ) );*/
	?>
</fieldset>
<fieldset>
    <?php
		echo $default->subform(
			array(
				'Serviceinstructeur.numdepins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numdepins', true ) ) ),
				'Serviceinstructeur.typeserins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.typeserins', true ) ), 'empty' => true ),
				'Serviceinstructeur.numcomins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numcomins', true ) ) ),
				'Serviceinstructeur.numagrins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numagrins', true ) ), 'maxlength' => 2 ),
			)
		);
	?>
</fieldset>
<?php if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ):?>
<fieldset>
    <?php
		echo $default->subform(
			array(
				'Serviceinstructeur.sqrecherche' => array( 'rows' => 40 ),
			)
		);
	?>
</fieldset>
<?php endif;?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  echo $form->create( 'Dossiersimplifie',array( 'url' => Router::url( null, true ) ) ); ?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        // Masquage des champs select si Statut = non orienté
        observeDisableFieldsOnValue( 'Orientstruct0StatutOrient', [ 'Orientstruct0TypeorientId', 'Orientstruct0StructurereferenteId', 'Orientstruct0StructureorientanteId', 'Orientstruct0ReferentorientantId' ], 'Non orienté', true );
        observeDisableFieldsOnValue( 'Orientstruct1StatutOrient', [ 'Orientstruct1TypeorientId', 'Orientstruct1StructurereferenteId', 'Orientstruct1StructureorientanteId', 'Orientstruct1ReferentorientantId'  ], 'Non orienté', true );
        // Masquage des champs select si non droit et devoir
        observeDisableFieldsOnValue( 'Calculdroitrsa0Toppersdrodevorsa', [ 'Orientstruct0TypeorientId', 'Orientstruct0StructurereferenteId', 'Orientstruct0StatutOrient', 'Orientstruct0StructureorientanteId', 'Orientstruct0ReferentorientantId' ], 0, true );
        observeDisableFieldsOnValue( 'Calculdroitrsa1Toppersdrodevorsa', [ 'Orientstruct1TypeorientId', 'Orientstruct1StructurereferenteId', 'Orientstruct1StatutOrient', 'Orientstruct1StructureorientanteId', 'Orientstruct1ReferentorientantId'  ], 0, true );
        ///

    });
</script>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'Orientstruct0ReferentorientantId', 'Orientstruct0StructureorientanteId' );
        dependantSelect( 'Orientstruct1ReferentorientantId', 'Orientstruct1StructureorientanteId' );
        dependantSelect( 'Orientstruct0StructurereferenteId', 'Orientstruct0TypeorientId' );
        dependantSelect( 'Orientstruct1StructurereferenteId', 'Orientstruct1TypeorientId' );

//         dependantSelect( 'Prestation1Rolepers', 'Prestation0Rolepers' );
    });
</script>

   <h1><?php echo $this->pageTitle = 'Ajout d\'une préconisation d\'orientation'; ?></h1>

        <fieldset>
            <h2>Dossier RSA</h2>
            <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => required( 'Numéro de demande RSA' ) ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => required( 'Date de demande' ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1 ) );?>
            <?php echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF' ) );?>
            <div><?php echo $form->input( 'Foyer.id', array( 'label' => required( __( 'id', true ) ), 'type' => 'hidden') );?></div>
        </fieldset>
        <fieldset>
            <h2>Personne à orienter</h2>
            <div><?php echo $form->input( 'Prestation.0.natprest', array( 'label' => false, 'value' => 'RSA', 'type' => 'hidden') );?></div>
            <!-- <div><?php echo $form->input( 'Prestation.0.rolepers', array( 'label' => false, 'value' => 'DEM', 'type' => 'hidden') );?></div> -->

            <?php echo $form->input( 'Prestation.0.rolepers', array( 'label' => required( __d( 'prestation', 'Prestation.rolepers', true ) ), 'type' => 'select', 'options' => $rolepers, 'empty' => true ) );?>


            <div><?php echo $form->input( 'Personne.0.id', array( 'label' => required( __( 'id', true ) ),  'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Personne.0.qual', array( 'label' => required( __d( 'personne', 'Personne.qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.0.nom', array( 'label' => required( __d( 'personne', 'Personne.nom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.prenom', array( 'label' => required( __d( 'personne', 'Personne.prenom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.nir', array( 'label' => ( __d( 'personne', 'Personne.nir', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.dtnai', array( 'label' => required( __d( 'personne', 'Personne.dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Calculdroitrsa.0.toppersdrodevorsa', array(  'label' =>  required( __d( 'calculdroitrsa', 'Calculdroitrsa.toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
        </fieldset>
        <fieldset>
            <h3>Orientation</h3>
            <?php 
                if( Configure::read( 'Cg.departement' ) == 66 ){
                    echo $form->input( 'Orientstruct.0.structureorientante_id', array( 'label' =>  'Structure orientante', 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );
                    echo $form->input( 'Orientstruct.0.referentorientant_id', array( 'label' =>  'Référent orientant', 'type' => 'select', 'options' => $refsorientants, 'empty' => true ) );
                }
            ?>
            <?php echo $form->input( 'Orientstruct.0.statut_orient', array( 'label' => "Statut de l'orientation", 'type' => 'select' , 'options' => $statut_orient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.0.typeorient_id', array( 'label' => "Type d'orientation / Type de structure", 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
          <!--  <?php echo $form->input( 'Typeorient.0.parent_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.0.typeorient_id', array( 'label' => __d( 'structurereferente', 'Structurereferente.lib_struc', true ), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?> -->
            <?php echo $form->input( 'Orientstruct.0.structurereferente_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.structure_referente_'.Configure::read( 'nom_form_ci_cg' ), true ), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>
        </fieldset>
        <fieldset>
            <h2>Autre personne à orienter (le cas échéant)</h2>
            <div><?php echo $form->input( 'Prestation.1.natprest', array( 'label' => false, 'value' => 'RSA', 'type' => 'hidden') );?></div>
            <!-- <div><?php echo $form->input( 'Prestation.1.rolepers', array( 'label' => false, 'value' => 'CJT', 'type' => 'hidden') );?></div> -->

            <?php echo $form->input( 'Prestation.1.rolepers', array( 'label' => __d( 'prestation', 'Prestation.rolepers', true ), 'type' => 'select', 'options' => $rolepers , 'empty' => true ) );?>

            <div><?php  echo $form->input( 'Personne.1.id', array( 'label' => required( __( 'id', true ) ), 'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Personne.1.qual', array( 'label' =>  __d( 'personne', 'Personne.qual', true ) , 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.1.nom', array( 'label' =>  __d( 'personne', 'Personne.nom', true )  ) );?>
            <?php echo $form->input( 'Personne.1.prenom', array( 'label' =>  __d( 'personne', 'Personne.prenom', true  ) ) );?>
            <?php echo $form->input( 'Personne.1.nir', array( 'label' =>  __d( 'personne', 'Personne.nir', true ) ) );?>
            <?php echo $form->input( 'Personne.1.dtnai', array( 'label' =>  __d( 'personne', 'Personne.dtnai', true  ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Calculdroitrsa.1.toppersdrodevorsa', array(  'label' =>   __d( 'calculdroitrsa', 'Calculdroitrsa.toppersdrodevorsa', true ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
        </fieldset>
        <fieldset>
            <h3>Orientation</h3>
            <?php 
                if( Configure::read( 'Cg.departement' ) == 66 ){
                    echo $form->input( 'Orientstruct.1.structureorientante_id', array( 'label' =>  'Structure orientante', 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );
                    echo $form->input( 'Orientstruct.1.referentorientant_id', array( 'label' =>  'Référent orientant', 'type' => 'select', 'options' => $refsorientants, 'empty' => true ) );
                }
            ?>
            <?php echo $form->input( 'Orientstruct.1.statut_orient', array( 'label' => "Statut de l'orientation", 'type' => 'select' , 'options' => $statut_orient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.1.typeorient_id', array( 'label' => "Type d'orientation / Type de structure", 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
          <!--  <?php echo $form->input( 'Typeorient.1.parent_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.1.typeorient_id', array( 'label' => __d( 'structurereferente', 'Structurereferente.lib_struc', true ), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?> -->
            <?php echo $form->input( 'Orientstruct.1.structurereferente_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.structure_referente_'.Configure::read( 'nom_form_ci_cg' ), true ), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>
        </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>
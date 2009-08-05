<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php  echo $form->create( 'Dossiersimplifie',array( 'url' => Router::url( null, true ) ) ); ?>
<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'PrestationToppersdrodevorsa', [ /*'TypeorientParentId', */'Orientstruct0TypeorientId', 'Orientstruct0StructurereferenteId' ], 0, true );

    });
</script>

<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'Orientstruct0StructurereferenteId', 'Orientstruct0TypeorientId' );
    });
</script>

<div class="with_treemenu">
   <h1><?php echo $this->pageTitle = 'Edition d\'une préconisation d\'orientation'; ?></h1>

        <fieldset>
            <h2>Dossier RSA</h2>
            <p><?php echo "Numéro de demande RSA : $numdossierrsa";?></p>
            <p><?php echo "Date de demande du dossier : $datdemdossrsa";?></p>
        </fieldset>
        <fieldset>
            <h2>Personne orientée</h2>
            <div><?php echo $form->input( 'Prestation.id', array( 'label' => false, 'type' => 'hidden') );?></div>
            <div><?php echo $form->input( 'Prestation.personne_id', array( 'label' => false, 'type' => 'hidden') );?></div>
            <div><?php echo $form->input( 'Prestation.natprest', array( 'label' => false, 'value' => 'RSA', 'type' => 'hidden') );?></div>

            <div><?php echo $form->input( 'Prestation.rolepers', array( 'label' => required( __( 'rolepers', true ) ), 'type' => 'hidden') );?></div>
            <div><?php echo $form->input( 'Personne.id', array( 'label' => required( __( 'id', true ) ), 'value' =>$id , 'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Personne.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.nom', array( 'label' => required( __( 'nom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.prenom', array( 'label' => required( __( 'prenom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.nir', array( 'label' => required( __( 'nir', true ) ) ) );?>
            <?php echo $form->input( 'Personne.dtnai', array( 'label' => required( __( 'dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Prestation.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
        </fieldset>
        <fieldset>
            <h3>Orientation</h3>
            <div><?php echo $form->input( 'Orientstruct.0.personne_id', array( 'label' => false, 'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Orientstruct.0.typeorient_id', array( 'label' => "Type d'orientation / Type de structure",'type' => 'select', 'selected'=> $orient_id, 'options' => $typesOrient, 'empty'=>true));?>
            <?php $this->data['Orientstruct'][0]['structurereferente_id'] = $this->data['Orientstruct'][0]['typeorient_id'].'_'.$this->data['Orientstruct'][0]['structurereferente_id'];?>
            <?php echo $form->input( 'Orientstruct.0.structurereferente_id', array( 'label' => __( 'structure_referente', true ), 'type' => 'select', 'selected' => $this->data['Orientstruct'][0]['structurereferente_id'], 'options' => $structures, 'empty' => true ) );?>
        </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>

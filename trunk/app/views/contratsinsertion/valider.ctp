<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Validation du contrat d\'insertion';?>

<?php  echo $form->create( 'Contratinsertion',array( 'url' => Router::url( null, true ) ) ); ?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<script type="text/javascript">
//     document.observe("dom:loaded", function() {
//         observeDisableFieldsOnValue( 'ContratinsertionDecisionCi', [ 'ContratinsertionDatevalidationCiDay', 'ContratinsertionDatevalidationCiMonth', 'ContratinsertionDatevalidationCiYear' ], 'V', false );
//     });
</script>
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

        <fieldset>
            <legend> PARTIE RESERVEE AU DEPARTEMENT</legend>
                <?php echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden'/*, 'value' => $personne_id*/ ) );?>
                <?php echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );?>
                <?php echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden' ) );?>

                <?php echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __( 'observ_ci', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
                <?php echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => __( 'decision_ci', true ), 'type' => 'select', 'options' => $decision_ci ) ); ?>
                <?php echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?><br />
                <!-- <?php echo $form->input( 'Contratinsertion.faita', array( 'label' => 'Fait à : ', 'type' => 'text')  ); ?><br />
                <?php echo $form->input( 'Contratinsertion.le ', array( 'label' => 'Le : ', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
                <?php echo $form->input( 'Contratinsertion.pcg ', array( 'label' => 'Le Président du Conseil Général : ', 'type' => 'text')  ); ?> -->
        </fieldset>

        <?php /*echo $form->submit( 'Enregistrer' );?> <?php echo $form->submit( 'Annuler' );*/?>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
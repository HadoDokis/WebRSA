<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Validation du CUI';?>

<?php  echo $form->create( 'Cui',array( 'url' => Router::url( null, true ) ) ); ?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

        <fieldset>
           <!-- <?php
/*
                echo $default->subform(
                    array(
//                         'Cui.id', array( 'type' => 'hidden' ),
//                         'Cui.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ),
//                         'Cui.structurereferente_id', array( 'type' => 'hidden' ),
                        'Cui.observcui',
                        'Cui.decisioncui', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.decision_ci', true ), 'type' => 'select', 'options' => $options['decisioncui'] ),
                        'Cui.datevalidationcui', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2, 'empty' => true)
                    ),
                    array(
                        'options' => $options
                    )
                );*/
            ?> -->
            <?php echo $xform->input( 'Cui.id', array( 'type' => 'hidden'/*, 'value' => $personne_id*/ ) );?>
            <?php echo $xform->input( 'Cui.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );?>
            <?php echo $xform->input( 'Cui.structurereferente_id', array( 'type' => 'hidden' ) );?>

            <?php echo $xform->input( 'Cui.observcui', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.observ_ci', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
            <?php echo $xform->input( 'Cui.decisioncui', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.decision_ci', true ), 'type' => 'select', 'options' => $options['decisioncui'], 'empty' => true ) ); ?>
            <?php echo $xform->input( 'Cui.datevalidationcui', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  ); ?>
        </fieldset>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
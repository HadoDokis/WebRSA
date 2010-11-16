<fieldset>
        <?php echo $form->input( 'Actioninsertion.id', array( 'type' => 'hidden' ) );?>
        <?php echo $form->input( 'Actioninsertion.lib_action', array( 'label' =>  __( 'lib_action', true ) , 'type' => 'radio', 'options' => $lib_action, 'empty' => true) );?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' )  ); ?>
        <?php echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' )  ); ?>

        <?php echo $form->input( 'Aidedirecte.typo_aide', array( 'label' => __( 'typo_aide', true ), 'type' => 'select', 'options' => $typo_aide, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Aidedirecte.lib_aide', array( 'label' => __( 'lib_aide', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Aidedirecte.date_aide', array( 'label' => __( 'date_aide', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true)  ); ?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Prestform.id', array( 'type' => 'hidden' )  ); ?>
        <?php echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden') ); ?> 
        <!-- <?php echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' )); ?> -->
        <?php echo $form->input( 'Prestform.lib_presta', array( 'label' => __( 'lib_presta', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Refpresta.nomrefpresta', array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text')); ?>
</fieldset>
<fieldset>
    <?php echo $form->input( 'Prestform.lib_presta', array( 'label' => required( __( 'lib_presta', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
    <?php echo $form->input( 'Refpresta.nomrefpresta', array( 'label' => required( __( 'nomrefpresta', true ) ), 'type' => 'text')); ?>
    <?php echo $form->input( 'Prestform.date_presta', array( 'label' => required( __( 'date_presta', true ) ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true )  ); ?>
</fieldset>

<fieldset>
    <?php echo $form->input( 'Typeorient.id', array( 'label' => 'id', 'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Typeorient.lib_type_orient', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Typeorient.parentid', array( 'label' => __( 'parentid', true ), 'type' => 'text' )  );?>
    <?php echo $form->input( 'Typeorient.modele_notif', array( 'label' => __( 'modele_notif', true ), 'type' => 'text' )  );?>   
   <?php /*echo $form->input( 'Typeorient.modele_notif', array( 'label' => __( 'modele_notif', true ), 'type' => 'select' , 'options' => $notif, 'empty' => true ) );*/?> 
</fieldset>
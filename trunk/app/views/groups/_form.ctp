<fieldset>
    <?php echo $form->input( 'Group.name', array( 'label' => required( __( 'name', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Group.parent_id', array( 'label' => required(  __( 'parent_id', true ) ), 'type' => 'text' ) );?>
</fieldset>
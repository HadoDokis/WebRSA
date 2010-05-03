<fieldset>
    <?php echo $form->input( 'Zonegeographique.libelle', array( 'label' => required( __( 'libelle', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Zonegeographique.codeinsee', array( 'label' => required( __( 'codeinsee', true ) ), 'type' => 'text', 'maxLength' => 5 ) );?>
</fieldset>
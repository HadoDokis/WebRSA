<fieldset>
    <?php echo $form->input( 'Typocontrat.lib_typo', array( 'label' =>  __( 'lib_typo', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Typocontrat.rang', array( 'label' =>  __( 'rang', true ), 'type' => 'select', 'options' => $rangs, 'empty' => true ) ); ?>
</fieldset>
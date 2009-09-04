<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ) ) );?>

  <fieldset>
        <legend>Recherche PDO</legend>
        <?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Filtre.typdero', array( 'label' => __( 'typdero', true ), 'type' => 'select', 'options' => $typdero, 'empty' => true ) );?>
        <?php echo $form->input( 'Filtre.avisdero', array( 'label' => __( 'avisdero', true ), 'type' => 'select', 'options' => $avisdero, 'empty' => true ) );?>
        <?php echo $form->input( 'Filtre.ddavisdero', array( 'label' => __( 'ddavisdero', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );?>
    </fieldset>
    <div class="submit">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>
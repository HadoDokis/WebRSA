<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Ajoutdossiers', array( 'id' => 'SignupForm', 'url'=> Router::url( null, true ) ) );?>
    <h1>Insertion d'une nouvelle demande de RSA</h1>
    <h2>Étape 1: demandeur RSA</h2>

    <?php echo $form->input( 'Prestation.natprest', array( 'type' => 'hidden', 'value' => 'RSA' ) );?>
    <?php echo $form->input( 'Prestation.rolepers', array( 'type' => 'hidden', 'value' => 'DEM' ) );?>
    <?php include( $this->__paths[0].'personnes/_form.ctp' ); /* FIXME */?>

    <div class="submit">
        <?php echo $form->submit( '< Précédent', array( 'name' => 'Previous', 'div'=>false, 'disabled' => true ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
        <?php echo $form->submit( 'Suivant >', array( 'div'=>false ) );?>
    </div>
<?php echo $form->end();?>
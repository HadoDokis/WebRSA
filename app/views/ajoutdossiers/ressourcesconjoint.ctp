<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Ajoutdossiers', array( 'id' => 'SignupForm', 'url'=> Router::url( null, true ) ) );?>
    <h1>Insertion d'une nouvelle demande de RSA</h1>
    <h2>Étape 3bis: ressources conjoint</h2>

    <?php include( $this->__paths[0].'ressources/_form.ctp' ); /* FIXME */?>

    <div class="submit">
        <?php echo $form->submit( '< Précédent', array( 'name' => 'Previous', 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
        <?php echo $form->submit( 'Suivant >', array( 'div'=>false ) );?>
    </div>
<?php echo $form->end();?>
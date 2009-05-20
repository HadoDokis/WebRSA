<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Tests', array( 'id' => 'SignupForm', 'url'=> Router::url( null, true ) ) );?>
    <h1>Insertion d'une nouvelle demande de RSA</h1>
    <h2>Étape 1bis: conjoint demandeur RSA</h2>

    <?php echo $form->input( 'Personne.rolepers', array( 'type' => 'hidden', 'value' => 'CJT' ) );?>
    <?php include( $this->__paths[0].'personnes/_form.ctp' ); /* FIXME */?>

    <div class="submit">
        <?php echo $form->submit( '< Précédent', array( 'name' => 'Previous', 'div'=>false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
        <?php echo $form->submit( 'Suivant >', array( 'div'=>false ) );?>
    </div>
<?php echo $form->end();?>
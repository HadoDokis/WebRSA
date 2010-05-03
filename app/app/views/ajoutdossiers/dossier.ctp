<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create('Ajoutdossiers',array('id'=>'SignupForm','url' => Router::url( null, true ) ) );?>
    <h1>Insertion d'une nouvelle demande de RSA</h1>
    <h2>Étape 4: Dossier RSA</h2>

    <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => required( 'Numéro de dossier' ) ) );?>
    <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => required( 'Date de demande' ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1 ) );?>

    <?php echo $form->input( 'Detaildroitrsa.oridemrsa', array( 'label' => required( 'Code origine demande Rsa' ), 'type' => 'select', 'options' => $oridemrsa ) );?>

    <?php echo $form->input( 'Ajoutdossier.serviceinstructeur_id', array( 'label' => required( __( 'lib_service', true ) ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>

    <div class="submit">
        <?php echo $form->submit( '< Précédent', array( 'name' => 'Previous', 'div'=>false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
        <?php echo $form->submit( 'Terminer', array( 'div'=>false ) );?>
    </div>
<?php echo $form->end();?>
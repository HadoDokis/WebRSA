    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsOnValue( 'PersonneQual', [ 'PersonneNomnai' ], 'MME', false );

        });
    </script>

    <fieldset>
        <legend>État civil</legend>
            <?php echo $form->input( 'Personne.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.nom', array( 'label' => required( __( 'nom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.nomnai', array( 'label' => __( 'nomnai', true ) ) );?>
            <?php echo $form->input( 'Personne.prenom', array( 'label' => required( __( 'prenom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.prenom2', array( 'label' => __( 'prenom2', true ) ) );?>
            <?php echo $form->input( 'Personne.prenom3', array( 'label' => __( 'prenom3', true ) ) );?>
            <?php echo $form->input( 'Personne.typedtnai', array( 'label' => __( 'typedtnai', true ), 'type' => 'select', 'options' => $typedtnai, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.dtnai', array( 'label' => required( __( 'dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.nomcomnai', array( 'label' => __( 'nomcomnai', true ) ) );?>
            <?php echo $form->input( 'Personne.rgnai', array( 'label' => required( __( 'rgnai', true ) ) , 'maxLength' => 2) );?>
            <?php echo $form->input( 'Personne.nir', array( 'label' => required( __( 'nir', true ) ) ) );?>
            <?php echo $form->input( 'Personne.topvalec', array( 'label' => __( 'topvalec', true ) ) );?>
    </fieldset>

    <fieldset>
        <legend>Nationalité</legend>
        <?php echo $form->input( 'Personne.nati', array( 'label' => required( __( 'nati', true ) ), 'type' => 'select', 'options' => $nationalite, 'empty' => true ) );?>
        <?php echo $form->input( 'Personne.dtnati', array( 'label' => __( 'dtnati', true ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
        <?php echo $form->input( 'Personne.pieecpres', array( 'label' => required( __( 'pieecpres', true ) ), 'type' => 'select', 'options' => $pieecpres, 'empty' => true ) );?>
    </fieldset>

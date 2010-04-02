    <script type="text/javascript">
        function checkDatesToRefresh() {
            if( ( $F( 'RessourceDdressMonth' ) ) && ( $F( 'RessourceDdressYear' ) ) ) {
                setDateInterval( 'RessourceDdress', 'RessourceDfress', 3, false );

                for( var i = 0 ; i <= 2 ; i++ ) {
                    setDateInterval( 'RessourceDdress', 'Ressourcemensuelle' + i + 'Moisress', i, true );
                    setDateInterval( 'RessourceDdress', 'Detailressourcemensuelle' + i + 'Dfpercress', i + 1, false );
                }
            }
        }

        document.observe("dom:loaded", function() {
            for( var i = 0 ; i < 3 ; i++ ) {
                observeDisableFieldsetOnCheckbox(
                    'RessourceTopressnotnul',
                    $( 'Ressourcemensuelle' + i + 'MoisressMonth' ).up( 'fieldset' ),
                    false
                );
            }

            Event.observe( $( 'RessourceDdressMonth' ), 'change', function() {
                checkDatesToRefresh();
            } );
            Event.observe( $( 'RessourceDdressYear' ), 'change', function() {
                checkDatesToRefresh();
            } );

        });
    </script>

    <fieldset>
        <legend>Généralités des ressources du trimestre</legend>
        <?php echo $form->input( 'Ressource.ddress', array( 'label' => required( __( 'ddress', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-20, 'empty' => true));?>
        <?php echo $form->input( 'Ressource.dfress', array( 'label' => required( __( 'dfress', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-20, 'empty' => true));?>
    </fieldset>

    <div><?php echo $form->input( 'Ressource.topressnotnul', array( 'label' => __( 'topressnotnul', true ), 'type' => 'checkbox' ) );?></div>

    <?php for( $i = 0 ; $i < 3 ; $i++ ):?>
        <fieldset>
            <legend>Ressources mensuelles</legend>
            <?php echo $form->input( 'Ressourcemensuelle.'.$i.'.moisress', array( 'label' => __( 'moisress', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-20, 'empty' => true) );?><!-- FIXME: la date ne doit pas comporter de jour à l'affichage -->
            <?php echo $form->input( 'Ressourcemensuelle.'.$i.'.nbheumentra', array( 'label' => __( 'nbheumentra', true ), 'type' => 'text', 'maxlength' => 3 ) );?>
            <?php echo $form->input( 'Ressourcemensuelle.'.$i.'.mtabaneu', array( 'label' => __( 'mtabaneu', true ), 'type' => 'text', 'maxlength' => 11 ) );?>

            <?php echo $form->input( 'Detailressourcemensuelle.'.$i.'.natress', array( 'label' => __( 'natress', true ), 'type' => 'select', 'options' => $natress, 'empty' => true ) );?>
            <?php echo $form->input( 'Detailressourcemensuelle.'.$i.'.mtnatressmen', array( 'label' => __( 'mtnatressmen', true ), 'type' => 'text', 'maxlength' => 11 ) );?>
            <?php echo $form->input( 'Detailressourcemensuelle.'.$i.'.abaneu', array( 'label' => __( 'abaneu', true ), 'type' => 'select', 'options' => $abaneu, 'empty' => true ) );?>
            <?php echo $form->input( 'Detailressourcemensuelle.'.$i.'.dfpercress', array( 'label' => __( 'dfpercress', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-20, 'empty' => true) );?>
            <?php echo $form->input( 'Detailressourcemensuelle.'.$i.'.topprevsubsress', array( 'label' => __( 'topprevsubsress', true ), 'type' => 'checkbox' ) );?>
        </fieldset>
    <?php endfor;?>
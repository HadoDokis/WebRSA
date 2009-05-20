    <fieldset>
        <?php echo $form->input( 'Infofinanciere.moismoucompta', array( 'label' => required( __( 'moismoucompta', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-20, 'empty' => true) );?>
        <?php echo $form->input( 'Infofinanciere.type_allocation', array( 'label' => required( __( 'type_allocation', true ) ), 'type' => 'select', 'options' => $type_alloc, 'empty' => true));?>
        <?php echo $form->input( 'Infofinanciere.natpfcre', array( 'label' => required( __( 'natpfcre', true ) ), 'type' => 'select', 'options' => $natpfcre, 'empty' => true));?>
        <?php echo $form->input( 'Infofinanciere.rgcre', array( 'label' => __( 'rgcre', true ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Infofinanciere.numintmoucompta', array( 'label' =>  __( 'numintmoucompta', true ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Infofinanciere.typeopecompta', array( 'label' => required( __( 'typeopecompta', true )), 'type' => 'select', 'options' => $typeopecompta, 'empty' => true) );?>
        <?php echo $form->input( 'Infofinanciere.sensopecompta', array( 'label' =>required(  __( 'sensopecompta', true ) ), 'type' => 'select', 'options' => $sensopecompta, 'empty' => true) );?>
        <?php echo $form->input( 'Infofinanciere.mtmoucompta', array( 'label' => required( __( 'mtmoucompta', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Infofinanciere.dttraimoucompta', array( 'label' => required( __( 'dttraimoucompta', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-20, 'empty' => true) );?>
       <!-- <?php echo $form->input( 'Infofinanciere.ddregu', array( 'label' => __( 'ddregu', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-20, 'empty' => true) );?>
        <?php echo $form->input( 'Infofinanciere.heutraimoucompta', array( 'label' => __( 'heutraimoucompta', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-20, 'empty' => true) );?>-->
    </fieldset>

    <script type="text/javascript">
        function woot( idSlave, idParent, valueParent, valuesSlaves ) {
            // Sauvegarde
            var select2Values = new Array();
            var select2Options = new Array();
            $$('#' + idSlave + ' option').each( function ( option ) {
                select2Values.push( option.value );
                select2Options.push( option.innerHTML );
            } );

            $( idParent ).observe( 'change', function( event ) {
                // Vidage de la liste
                $$('#' + idSlave + ' option').each( function ( option ) {
                    $(option).remove();
                } );

                // Re-remplissage
                if( $F( $( idParent ) ) == valueParent ) {
                    for( var i = 0 ; i < select2Values.length ; i++ ) {
                        if( ( valuesSlaves.include( select2Values[i] ) ) || ( select2Values[i] == '' ) ) {
                            alert( $( idSlave ) );
                            $( idSlave ).insert( new Element( 'option', { 'value': select2Values[i] } ).update( select2Options[i] ) );
                        }
                    }
                }
            } );
        }

        //*********************************************************************

        // FIXME
        /*woot( 'InfofinanciereNatpfcre', 'InfofinanciereTypeAllocation', 1, [ 'RSD', 'RSI', 'RSB', 'RCB', 'ASD', 'VSD', 'INK', 'INL', 'INM', 'ITK', 'ITL', 'ITM', 'ISK' ] );
        woot( 'InfofinanciereNatpfcre', 'InfofinanciereTypeAllocation', 3, [ 'INK', 'INL', 'INM', 'ITK', 'ITL', 'ITM'] );

        // Vidage de la liste esclave
        $$('#InfofinanciereNatpfcre option').each( function ( option ) {
            $(option).remove();
        } );*/
    </script>

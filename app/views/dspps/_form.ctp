    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsOnCheckbox( 'DsppDrorsarmiant1', [ 'DsppDrorsarmianta20', 'DsppDrorsarmianta21' ], false );
            observeDisableFieldsOnCheckbox( 'DsppDrorsarmiant0', [ 'DsppDrorsarmianta20', 'DsppDrorsarmianta21' ], true );
            observeDisableFieldsOnCheckbox( 'DifsocDifsoc7', [ 'DsppLibautrdifsoc' ], false );
            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi6', [ 'DsppLibautraccosocindi' ], false );
            observeDisableFieldsOnCheckbox( 'DsppElopersdifdisp1', [ 'DsppObstemploidifdisp1', 'DsppObstemploidifdisp0' ], false );
            observeDisableFieldsOnCheckbox( 'DsppElopersdifdisp0', [ 'DsppObstemploidifdisp1', 'DsppObstemploidifdisp0' ], true );

            observeDisableFieldsOnCheckbox( 'AccoemploiAccoemploi1', [ 'DsppLibcooraccoemploi', 'AccoemploiAccoemploi2', 'AccoemploiAccoemploi3', 'DsppLibcooraccoemploi' ], true );

            //observeDisableFieldsOnValue( 'AccoemploiAccoemploi1', [ 'DsppLibcooraccoemploi', ], '1801', true );
            observeDisableFieldsOnValue( 'DsppHispro', [ 'DsppLibderact', 'DsppLibsecactderact', 'DsppDfderactDay', 'DsppDfderactMonth', 'DsppDfderactYear', 'DsppDomideract1','DsppDomideract0', 'DsppLibactdomi', 'DsppLibsecactdomi', 'DsppDuractdomi' ], '1904', true );
            observeDisableFieldsOnCheckbox( 'DifsocDifsoc1', [ 'DifsocDifsoc2', 'DifsocDifsoc3', 'DifsocDifsoc4', 'DifsocDifsoc5', 'DifsocDifsoc6', 'DifsocDifsoc7' ], true );

            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi1', [ 'NataccosocindiNataccosocindi2', 'NataccosocindiNataccosocindi3', 'NataccosocindiNataccosocindi4', 'NataccosocindiNataccosocindi5', 'NataccosocindiNataccosocindi6' ], true );

        });
    </script>
<!-- /************************* Modif CG93 *******************************/ 
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsOnCheckbox(
                'DspfAccosocfam',
                [ 'DspfLibcooraccosocfam' ],
                false
            );

            observeDisableFieldsetOnCheckbox(
                'DspfAccosocfam',
                $( 'NataccosocfamNataccosocfam1' ).up( 'fieldset' ),
                false
            );


            observeDisableFieldsOnCheckbox(
                'NataccosocfamNataccosocfam4',
                [ 'DspfLibautraccosocfam' ],
                false
            );

            observeDisableFieldsOnCheckbox(
                'DiflogDiflog1',
                [ 'DspfLibautrdiflog', 'DiflogDiflog2', 'DiflogDiflog3', 'DiflogDiflog4', 'DiflogDiflog5', 'DiflogDiflog6', 'DiflogDiflog7', 'DiflogDiflog8', 'DiflogDiflog9' ],
                true
            );

            observeDisableFieldsOnCheckbox(
                'DiflogDiflog9',
                [ 'DspfLibautrdiflog' ],
                false
            );
        });
    </script>
<fieldset>
    <legend>Généralités DSPF</legend>
    <?php echo $form->input( 'Dspf.motidemrsa', array( 'label' =>  __( 'motidemrsa', true ), 'type' => 'select', 'options' => $motidemrsa, 'empty' => true ) );?>
</fieldset>

<fieldset>
    <legend>Accompagnement social familial</legend>
    <?php echo $form->input( 'Dspf.accosocfam', array( 'label' => __( 'accosocfam', true ), 'type' => 'checkbox' ) );?>
    <?php echo $form->input( 'Dspf.libcooraccosocfam', array( 'label' => __( 'libcooraccosocfam', true ), 'type' => 'textarea', 'rows' =>3) );?>

    <fieldset>
        <legend><?php echo __( 'nataccosocfam', true ) ?></legend>
        <?php echo $form->input( 'Nataccosocfam.Nataccosocfam', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $nataccosocfams ) );?>
        <?php echo $form->input( 'Dspf.libautraccosocfam', array( 'label' =>  __( 'libautraccosocfam', true ), 'type' => 'textarea', 'rows' => 3 ) );?>
    </fieldset>

    <fieldset class="col2">
        <legend>Difficultés de logement</legend>
        <?php echo $form->input( 'Dspf.natlog', array( 'label' =>  __( 'natlog', true ), 'type' => 'select', 'options' => $natlog, 'empty' => true ) );?>
        <?php echo $form->input( 'Dspf.demarlog', array( 'label' => __( 'demarlog', true ), 'type' => 'select', 'options' => $demarlog, 'empty' => true ) );?>
        <?php echo $form->input( 'Diflog.Diflog', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $diflogs ) );?>
        <div class="clearer"><br /></div>
        <?php echo $form->input( 'Dspf.libautrdiflog', array( 'label' => __( 'libautrdiflog', true ), 'type' => 'textarea', 'rows' => 3 ) );?>
    </fieldset>
</fieldset>

 -->
    <fieldset>
            <legend>Généralités DSPP</legend>
                <!-- <?php /*echo $form->input( 'Serviceinstructeur.lib_service', array( 'label' => __( 'lib_service' ,true), 'type' => 'select', 'options' => $typeservices, 'empty' => true ) );*/?> -->
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'drorsarmiant', true ) ); 
                    echo $form->radio( 'Dspp.drorsarmiant', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'drorsarmianta2', true )); 
                    echo $form->radio( 'Dspp.drorsarmianta2', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'couvsoc', true ) ); 
                    echo $form->radio( 'Dspp.couvsoc', $options, $attributes );
                ?>
    </fieldset>
    <fieldset>
            <legend>Situation sociale</legend>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'elopersdifdisp', true ) ); 
                    echo $form->radio( 'Dspp.elopersdifdisp', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'obstemploidifdisp', true ) ); 
                    echo $form->radio( 'Dspp.obstemploidifdisp', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'soutdemarsoc', true ) ); 
                    echo $form->radio( 'Dspp.soutdemarsoc', $options, $attributes );
                ?>
                <?php echo $form->input( 'Dspp.libcooraccosocindi', array( 'label' => __( 'libcooraccosocindi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
    </fieldset>


    <fieldset class="col2">
        <legend><?php echo __( 'difsoc', true ) ?></legend>
            <?php echo $form->input( 'Difsoc.Difsoc', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $difsocs ) );?>
                <?php echo $form->input( 'Dspp.libautrdifsoc', array( 'label' => __( 'libautrdifsoc', true ), 'type' => 'text') );?>
    </fieldset>
    <fieldset class="col2">
        <legend><?php echo __( 'nataccosocindi', true ) ?></legend>
        <?php echo $form->input( 'Nataccosocindi.Nataccosocindi', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $nataccosocindis ) );?>
        <?php echo $form->input( 'Dspp.libautraccosocindi', array( 'label' => __( 'libautraccosocindi', true ), 'type' => 'text' ) );?>
    </fieldset>
    <fieldset class="col2">
        <legend><?php echo __( 'difdisp', true ) ?></legend>
    <?php echo $form->input( 'Difdisp.Difdisp', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $difdisps ) );?>
    </fieldset>
    <fieldset>
            <legend>Niveau d'étude</legend>
                <?php echo $form->input( 'Dspp.annderdipobt', array( 'label' =>  __( 'annderdipobt', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'rappemploiquali', true ), 'label' => false ); 
                    echo $form->radio( 'Dspp.rappemploiquali', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'rappemploiform', true ), 'label' => false ); 
                    echo $form->radio( 'Dspp.rappemploiform', $options, $attributes );
                ?>
                <?php echo $form->input( 'Dspp.libautrqualipro', array( 'label' => __( 'libautrqualipro', true ), 'type' => 'text' ) );?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'permicondub', true ), 'label' => false ); 
                    echo $form->radio( 'Dspp.permicondub', $options, $attributes );
                ?>
                <?php echo $form->input( 'Dspp.libautrpermicondu', array( 'label' => __( 'libautrpermicondu', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libcompeextrapro', array( 'label' => __( 'libcompeextrapro', true ), 'type' => 'text' ) );?>
                <legend><?php echo __( 'nivetu', true ) ?></legend>
                <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
    </fieldset>

    <fieldset>
            <legend>Situation professionnelle</legend>
                <legend><?php echo __( 'accoemploi', true ) ?></legend>
                <?php echo $form->input( 'Accoemploi.Accoemploi', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $accoemplois ) );?>
                <?php echo $form->input( 'Dspp.libcooraccoemploi', array( 'label' => __( 'libcooraccoemploi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
                <?php echo $form->input( 'Dspp.hispro', array( 'label' =>  required( __( 'hispro', true ) ), 'type' => 'select', 'options' => $hispro, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libderact', array( 'label' => __( 'libderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactderact', array( 'label' => __( 'libsecactderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.dfderact', array( 'label' => required( __( 'dfderact', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true ) );?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'domideract', true ) ); 
                    echo $form->radio( 'Dspp.domideract', $options, $attributes );
                ?>
                <?php echo $form->input( 'Dspp.libactdomi', array( 'label' => __( 'libactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactdomi', array( 'label' => __( 'libsecactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.duractdomi', array( 'label' => required( __( 'duractdomi', true ) ), 'type' => 'select', 'options' => $duractdomi, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
            <legend>Métier recherché</legend>
                <?php echo $form->input( 'Dspp.libemploirech', array( 'label' => __( 'libemploirech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactrech', array( 'label' => __( 'libsecactrech', true ), 'type' => 'text' ) );?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'creareprisentrrech', true ) ); 
                    echo $form->radio( 'Dspp.creareprisentrrech', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'moyloco', true ) ); 
                    echo $form->radio( 'Dspp.moyloco', $options, $attributes );
                ?>
                <?php
                    $options = array( '1' => 'Oui','0' => 'Non'); 
                    $attributes = array( 'legend' => __( 'persisogrorechemploi', true ), 'label' => false ); 
                    echo $form->radio( 'Dspp.persisogrorechemploi', $options, $attributes );
                ?>
    </fieldset>
    <fieldset>
        <legend><?php echo __( 'natmob', true ) ?> </legend>
            <?php echo $form->input( 'Natmob.Natmob', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $natmobs ) );?>
    </fieldset>
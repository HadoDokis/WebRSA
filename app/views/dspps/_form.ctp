    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsOnCheckbox( 'DsppDrorsarmiant', [ 'DsppDrorsarmianta2' ], false );
            observeDisableFieldsOnCheckbox( 'DifsocDifsoc7', [ 'DsppLibautrdifsoc' ], false );
            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi6', [ 'DsppLibautraccosocindi' ], false );
            observeDisableFieldsOnCheckbox( 'DsppElopersdifdisp', [ 'DsppObstemploidifdisp' ], false );
            observeDisableFieldsOnCheckbox( 'AccoemploiAccoemploi1', [ 'DsppLibcooraccoemploi', 'AccoemploiAccoemploi2', 'AccoemploiAccoemploi3', 'DsppLibcooraccoemploi' ], true );

            //observeDisableFieldsOnValue( 'AccoemploiAccoemploi1', [ 'DsppLibcooraccoemploi', ], '1801', true );
            observeDisableFieldsOnValue( 'DsppHispro', [ 'DsppLibderact', 'DsppLibsecactderact', 'DsppDfderactDay', 'DsppDfderactMonth', 'DsppDfderactYear', 'DsppDomideract', 'DsppLibactdomi', 'DsppLibsecactdomi', 'DsppDuractdomi', 'DsppLibemploirech', 'DsppLibsecactrech' ], '1904', true );
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
                <?php echo $form->input( 'Dspp.drorsarmiant', array( 'label' => __( 'drorsarmiant', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.drorsarmianta2', array( 'label' => __( 'drorsarmianta2', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.couvsoc', array( 'label' => __( 'couvsoc', true )));?>
    </fieldset>
    <fieldset>
            <legend>Situation sociale</legend>
                <?php echo $form->input( 'Dspp.elopersdifdisp', array( 'label' => __( 'elopersdifdisp', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.obstemploidifdisp', array( 'label' => __( 'obstemploidifdisp', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.soutdemarsoc', array( 'label' => __( 'soutdemarsoc', true ), 'type' => 'checkbox' ) );?>
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
                <?php echo $form->input( 'Dspp.rappemploiquali', array( 'label' => __( 'rappemploiquali', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.rappemploiform', array( 'label' => __( 'rappemploiform', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libautrqualipro', array( 'label' => __( 'libautrqualipro', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.permicondub', array( 'label' => __( 'permicondub', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libautrpermicondu', array( 'label' => __( 'libautrpermicondu', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libcompeextrapro', array( 'label' => __( 'libcompeextrapro', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => __( 'nivetu', true ), 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
    </fieldset>

    <fieldset>
            <legend>Situation professionnelle</legend>
                <?php echo $form->input( 'Accoemploi.Accoemploi', array( 'label' => __( 'accoemploi', true ), 'div' => false, 'multiple' => 'checkbox', 'options' => $accoemplois ) );?>
                <?php echo $form->input( 'Dspp.libcooraccoemploi', array( 'label' => __( 'libcooraccoemploi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
                <?php echo $form->input( 'Dspp.hispro', array( 'label' =>  __( 'hispro', true ), 'type' => 'select', 'options' => $hispro, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libderact', array( 'label' => __( 'libderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactderact', array( 'label' => __( 'libsecactderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.dfderact', array( 'label' => __( 'dfderact', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.domideract', array( 'label' => __( 'domideract', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libactdomi', array( 'label' => __( 'libactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactdomi', array( 'label' => __( 'libsecactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.duractdomi', array( 'label' =>  __( 'duractdomi', true ), 'type' => 'select', 'options' => $duractdomi, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libemploirech', array( 'label' => __( 'libemploirech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactrech', array( 'label' => __( 'libsecactrech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.creareprisentrrech', array( 'label' => __( 'creareprisentrrech', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.moyloco', array( 'label' => __( 'moyloco', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.persisogrorechemploi', array( 'label' => __( 'persisogrorechemploi', true ), 'type' => 'checkbox' ) );?>
    </fieldset>
    <fieldset>
        <legend><?php echo __( 'natmob', true ) ?> </legend>
            <?php echo $form->input( 'Natmob.Natmob', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $natmobs ) );?>
    </fieldset>
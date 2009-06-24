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
        <?php echo $form->input( 'Foyer.sitfam', array( 'label' =>  __( 'sitfam', true ), 'type' => 'select', 'options' => $sitfam, 'empty' => true ) ); ?>
        <?php echo $form->input( 'Foyer.typeocclog', array( 'label' =>  __( 'typeocclog', true ), 'type' => 'select', 'options' => $typeocclog, 'empty' => true ) ); ?>
</fieldset>

<fieldset>
    <legend>Accompagnement social familial</legend>
    <?php echo $form->input( 'Dspf.accosocfam', array( 'label' => __( 'accosocfam', true ), 'type' => 'select', 'options' => $accosocfam, 'empty' => true ) );?>
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



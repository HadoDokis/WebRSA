<!--<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnRadioValue(
            'DossierepDecisioncg',
            'data[Decisionsaisinepdoep66][motifpdo]',
            $( 'nonadmis' ),
            'N',
            false,
            true
        );
    });
</script>-->

<?php
	$domain = 'pdo';
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>
<fieldset id="Decision" class="invisible">
    <?php
    	echo $form->create('Dossierep', array('url'=>'/dossierseps/decisioncg/'.$dossierep_id, 'id'=>'DossierepDecisioncg'));

    	if (isset($this->data['Decisionsaisinepdoep66']['id']))
	    	echo $form->input('Decisionsaisinepdoep66.id', array('type'=>'hidden'));

    	echo $form->input('Decisionsaisinepdoep66.saisinepdoep66_id', array('type'=>'hidden'));
    	echo $form->input('Saisinepdoep66.id', array('type'=>'hidden'));
    	echo $form->input('Decisionsaisinepdoep66.etape', array('type'=>'hidden', 'value'=>'cg'));
    	echo $form->input('Saisinepdoep66.dossierep_id', array('type'=>'hidden', 'value' => $dossierep_id ));

        echo $default->subform(
            array(
                'Decisionsaisinepdoep66.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                'Decisionsaisinepdoep66.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                //'Decisionsaisinepdoep66.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                //'Decisionsaisinepdoep66.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
            ),
            array(
                'domain' => $domain,
                'options' => $options
            )
        );
    ?>
        <!--<fieldset id="nonadmis" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Decisionsaisinepdoep66.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
        </fieldset>-->
    <?php
        echo $default->subform(
            array(
                'Decisionsaisinepdoep66.commentaire' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
             ),
            array(
                'domain' => $domain,
                'options' => $options
            )
        );

        echo $form->end('Enregistrer');
    ?>
</fieldset>

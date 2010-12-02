<!--<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnRadioValue(
            'DossierepDecisioncg',
            'data[Nvsepdpdo66][motifpdo]',
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
    	
    	if (isset($this->data['Nvsepdpdo66']['id']))
	    	echo $form->input('Nvsepdpdo66.id', array('type'=>'hidden'));
    	
    	echo $form->input('Nvsepdpdo66.saisineepdpdo66_id', array('type'=>'hidden'));
    	echo $form->input('Saisineepdpdo66.id', array('type'=>'hidden'));
    	echo $form->input('Nvsepdpdo66.etape', array('type'=>'hidden', 'value'=>'cg'));
    	echo $form->input('Dossierep.id', array('type'=>'hidden'));
    	
        echo $default->subform(
            array(
                'Nvsepdpdo66.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                'Nvsepdpdo66.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                //'Nvsepdpdo66.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                //'Nvsepdpdo66.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
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
                        'Nvsepdpdo66.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
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
                'Nvsepdpdo66.commentaire' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
             ),
            array(
                'domain' => $domain,
                'options' => $options
            )
        );
        
        echo $form->end('Enregistrer');
    ?>
</fieldset>

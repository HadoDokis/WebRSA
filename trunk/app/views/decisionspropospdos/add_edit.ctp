<?php
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>


<div class="with_treemenu">
    <?php
        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( 'decisionpropopdo', "Decisionspropospdos::{$this->action}", true )
        );

    ?>
    <?php   
        echo $xform->create( 'Decisionpropopdo', array( 'id' => 'decisionpropopdoform' ) );
        if( Set::check( $this->data, 'Decisionpropopdo.id' ) ){
            echo $xform->input( 'Decisionpropopdo.id', array( 'type' => 'hidden' ) );
        }
         echo $xform->input( 'Decisionpropopdo.propopdo_id', array( 'type' => 'hidden', 'value' => $propopdo_id ) );
    ?>
    
    <fieldset><legend>Proposition de décision</legend>
        <fieldset id="Decision" class="invisible">
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        'Decisionpropopdo.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'required' => true, 'empty' => true )
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.commentairepdo' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
                     ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

    </fieldset>

    <fieldset><legend>Avis technique</legend>
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.avistechnique' => array( 'label' => false, 'type' => 'radio', 'options' => $options['avistechnique'] ),
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
            <fieldset id="avistech" class="noborder">
                <?php
                    echo $default2->subform(
                    array(
                        'Decisionpropopdo.commentaireavistechnique',
                        'Decisionpropopdo.dateavistechnique' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                    ),
                    array(
                        'options' => $options
                    )
                );
                ?>
            </fieldset>
    </fieldset>

    <fieldset><legend>Validation de la proposition</legend>
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.validationdecision' => array( 'label' => false, 'type' => 'radio', 'options' => $options['validationdecision'] ),
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
            <fieldset id="validpropo" class="noborder">
                <?php
                    echo $default2->subform(
                    array(
                        'Decisionpropopdo.commentairedecision',
                        'Decisionpropopdo.datevalidationdecision' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                    ),
                    array(
                        'options' => $options
                    )
                );
                ?>
            </fieldset>
    </fieldset>
	
	<?php
        echo "<div class='submit'>";
			echo $form->submit('Enregistrer', array('div'=>false));
			echo $form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( '/propospdos/edit/'.$propopdo_id, true )."')" ) );
        echo "</div>";
        
        echo $form->end();
    ?>

    <?php echo $xform->end();?>
</div>
            
<script type="text/javascript">
    document.observe("dom:loaded", function() {


        /*observeDisableFieldsOnRadioValue(
            'decisionpropopdoform',
            'data[Decisionpropopdo][avistechnique]',
            [
                'DecisionpropopdoCommentaireavistechnique'
            ],
            ['O','N'],
            true,
            true
        );*/

        observeDisableFieldsetOnRadioValue(
            'decisionpropopdoform',
            'data[Decisionpropopdo][avistechnique]',
            $( 'avistech' ),
            ['O','N'],
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'decisionpropopdoform',
            'data[Decisionpropopdo][validationdecision]',
            $( 'validpropo' ),
            ['O','N'],
            false,
            true
        );

    } );
</script>
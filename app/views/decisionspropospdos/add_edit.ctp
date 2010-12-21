<?php
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>


<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'DecisionpropopdoIsvalidation', $( 'DecisionpropopdoDatevalidationdecisionDay' ).up( 'fieldset' ), false );
    });
</script>

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
    
    <fieldset><legend>Décision</legend>
        <fieldset id="Decision" class="invisible">
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        'Decisionpropopdo.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                        //'Decisionpropopdo.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        //'Decisionpropopdo.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
                <!--<fieldset id="nonadmis" class="invisible">
                    <?php
                        echo $default->subform(
                            array(
                                'Decisionpropopdo.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </fieldset>-->
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.commentairepdo' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
                     ),
                    array(
                        'options' => $options
                    )
                );
                //echo $ajax->observeField( 'DecisionpropopdoDecisionpdoId', array( 'update' => 'Etatpdo4', 'url' => Router::url( array( 'action' => 'ajaxetat4' ), true ) ) );
            ?>
        </fieldset>

    </fieldset>

    <fieldset id="Etatpdo4" class="invisible"></fieldset>
        
    <fieldset>
        <?php
            echo $form->input( 'Decisionpropopdo.isvalidation', array( 'label' => 'Validation', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Isvalidation" class="invisible">
            <?php
                echo $default2->subform(
                    array(
                        'Decisionpropopdo.validationdecision' => array( 'type' => 'radio', 'options' => $options['validationdecision'] ),
                        'Decisionpropopdo.datevalidationdecision' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                    ),
                    array(
                        'options' => $options
                    )
                );
                //echo $ajax->observeField( 'DecisionpropopdoIsvalidation', array( 'update' => 'Etatpdo3', 'url' => Router::url( array( 'action' => 'ajaxetat3' ), true ) ) );
            ?>
        </fieldset>
    </fieldset>

    <fieldset id="Etatpdo3" class="invisible"></fieldset>
	
	<?php
        echo "<div class='submit'>";
			echo $form->submit('Enregistrer', array('div'=>false));
			echo $form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( '/propospdos/edit/'.$propopdo_id, true )."')" ) );
        echo "</div>";
        
        echo $form->end();
    ?>

    <?php echo $xform->end();?>
</div>

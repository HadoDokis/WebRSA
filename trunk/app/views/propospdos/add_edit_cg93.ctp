<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Validation PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une PDO';
    }
    else {
        $this->pageTitle = 'Édition de la PDO';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php

        if( $this->action == 'add' ) {
            echo $xform->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//             echo $xform->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );
        }
        else {
            echo $xform->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $xform->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
//             echo $xform->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );

            echo '</div>';
        }
        echo '<div>';
        echo $xform->input( 'Propopdo.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <legend>Détails PDO</legend>
            <?php
                echo $xform->input( 'Propopdo.structurereferente_id', array( 'label' =>  $xform->_label( __( 'Structure gérant la PDO', true ), array( 'required' => true ) ), 'type' => 'select', 'options' => $structs, 'empty' => true ) );
                echo $xform->input( 'Propopdo.typepdo_id', array( 'label' =>  $xform->_label( __d( 'propopdo', 'Propopdo.typepdo', true ), array( 'required' => true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.datereceptionpdo', array( 'label' =>  ( __( 'Date de réception de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
                echo $xform->input( 'Propopdo.originepdo_id', array( 'label' =>  $xform->_label( __( 'Origine', true ), array( 'required' => true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.decision', array( 'type' => 'hidden', 'value' => '1' ) ).$xform->input( 'Propopdo.decisionpdo_id', array( 'label' =>  $xform->_label( __( 'Décision du Conseil Général', true ), array( 'required' => true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.motifpdo', array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.iscomplet', array( 'label' =>  $xform->_label( __( 'Etat du dossier', true ), array( 'required' => true ) ), 'type' => 'radio', 'options' => $options['iscomplet'], 'empty' => true ) );
//                 echo $xform->input( 'Propopdo.statutdecision', array( 'label' =>  ( __( 'Statut de la décision', true ) ), 'type' => 'select', 'options' => $options['statutdecision'], 'empty' => true ) );

                echo $xform->input( 'Propopdo.datedecisionpdo', array( 'label' =>  ( __( 'Date de décision CG', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );

                echo $xform->input( 'Propopdo.commentairepdo', array( 'label' =>  'Observations', 'type' => 'text', 'rows' => 3, 'empty' => true ) );

                echo $default->view(
                    $dossier,
                    array(
                        'Dossier.fonorg',
                        'Suiviinstruction.typeserins',
                    ),
                    array(
                        'widget' => 'table',
                        'id' => 'dossierInfosOrganisme',
                        'options' => $options
                    )
                );

                /**
                *   Ajouts de checkbox multiples pour les statuts et situations
                */
//                 echo $xform->input( 'Statutpdo.Statutpdo', array( 'label' => 'Statut de la PDO', 'multiple' => 'checkbox' , 'options' => $statutlist ) );
//                 echo $xform->input( 'Situationpdo.Situationpdo', array( 'label' => 'Situation de la PDO', 'multiple' => 'checkbox' , 'options' => $situationlist ) );

            ?>
            <table class="noborder" id="infosPdo">
                <tr>
                    <td class="noborder">
                        <?php
                            echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => 'Situation de la PDO', 'multiple' => 'checkbox' , 'options' => $situationlist ) );
                        ?>
                    </td>
                    <td class="noborder">
                        <?php
                            echo $xform->input( 'Statutpdo.Statutpdo', array( 'type' => 'select', 'label' => 'Statut de la PDO', 'multiple' => 'checkbox' , 'options' => $statutlist ) );
                        ?>
                    </td>

                </tr>
            </table>
        </fieldset>
    </div>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>

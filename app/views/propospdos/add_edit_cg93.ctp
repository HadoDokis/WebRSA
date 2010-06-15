<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

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
                echo $xform->input( 'Propopdo.typepdo_id', array( 'label' =>  ( __( 'typepdo', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.datereceptionpdo', array( 'label' =>  ( __( 'Date de réception de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
                echo $xform->input( 'Propopdo.originepdo_id', array( 'label' =>  ( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.decisionpdo_id', array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
                echo $xform->input( 'Propopdo.motifpdo', array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );
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
                    <td class="noborder">
                        <?php
                            echo $xform->input( 'Statutdecisiondo.Statutdecisiondo', array( 'type' => 'select', 'label' => 'Thème de la PDO', 'multiple' => 'checkbox' , 'options' => $statutdecisionlist ) );
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
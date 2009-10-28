<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Rendez-vous';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout Rendez-vous';
    }
    else {
        $this->pageTitle = 'Édition Rendez-vous';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Rendezvous', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'Rendezvous', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Rendezvous.id', array( 'type' => 'hidden' ) );
//             echo $form->input( 'Rendezvous.structurereferente_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Rendezvous.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <?php
                echo $form->input( 'Rendezvous.structurereferente_id', array( 'label' =>  required( __( 'lib_struct', true ) ), 'type' => 'select', 'options' => $struct, 'empty' => true ) );
                echo $form->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) );
                ///Ajax
                echo $ajax->observeField( 'RendezvousStructurereferenteId', array( 'update' => 'RendezvousReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );

                echo $html->tag(
                    'div',
                    $html->tag( 'span', 'Fonction du référent', array( 'class' => 'label' ) ).
                    $html->tag( 'span', ( isset( $ReferentFonction ) ? $ReferentFonction : null ), array( 'id' => 'ReferentFonction', 'class' => 'input' ) ),
                    array( 'class' => 'input text' )
                );
                echo $ajax->observeField( 'RendezvousReferentId', array( 'update' => 'ReferentFonction', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );

                ///Ajout d'une permanence liée à une structurereferente
                echo $form->input( 'Rendezvous.permanence_id', array( 'label' => 'Permanence liée à la structure', 'type' => 'select', 'options' => $permanences, 'empty' => true ) );
                echo $ajax->observeField( 'RendezvousStructurereferenteId', array( 'update' => 'RendezvousPermanenceId', 'url' => Router::url( array( 'action' => 'ajaxperm' ), true ) ) );

                echo $form->input( 'Rendezvous.typerdv_id', array( 'label' =>  required( __( 'lib_rdv', true ) ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) );
            ?>
            <?php echo $form->input( 'Rendezvous.statutrdv_id', array( 'label' =>  required( __( 'statutrdv', true ) ), 'type' => 'select', 'options' => $statutrdv, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.daterdv', array( 'label' =>  required( __( 'daterdv', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 ) );?>
            <?php
                echo $xform->input( 'Rendezvous.heurerdv', array( 'label' =>  required( __( 'heurerdv', true ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5,  'empty' => true, 'hourRange' => array( 8, 19 ) ) );
            ?>
            <?php echo $form->input( 'Rendezvous.objetrdv', array( 'label' =>  ( __( 'objetrdv', true ) ), 'type' => 'text', 'rows' => 2, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.commentairerdv', array( 'label' =>  ( __( 'commentairerdv', true ) ), 'type' => 'text', 'rows' => 3, 'empty' => true ) );?>
        </fieldset>
    </div>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
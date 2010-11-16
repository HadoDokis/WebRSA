<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

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
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'RendezvousPermanenceId', 'RendezvousStructurereferenteId' );
        dependantSelect( 'RendezvousReferentId', 'RendezvousStructurereferenteId' );


        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ReferentFonction',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxreffonct',
                            Set::extract( $this->data, 'Rendezvous.referent_id' )
                        ),
                        true
                    )
                )
            );
        ?>
    });
</script>

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
                echo $form->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom de l\'agent / du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );
                ///Ajax
//                 echo $ajax->observeField( 'RendezvousStructurereferenteId', array( 'update' => 'RendezvousReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );
// debug($struct);
                echo $ajax->observeField( 'RendezvousReferentId', array( 'update' => 'ReferentFonction', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );

                echo $xhtml->tag(
                    'div',
                    '<b></b>',
                    array(
                        'id' => 'ReferentFonction'
                    )
                );

                ///Ajout d'une permanence liée à une structurereferente
                echo $form->input( 'Rendezvous.permanence_id', array( 'label' => 'Permanence liée à la structure', 'type' => 'select', 'options' => $permanences, 'selected' => $struct_id.'_'.$permanence_id, 'empty' => true ) );


                echo $form->input( 'Rendezvous.typerdv_id', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.lib_rdv', true ) ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) );
            ?>
            <?php echo $form->input( 'Rendezvous.statutrdv_id', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.statutrdv', true ) ), 'type' => 'select', 'options' => $statutrdv, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.daterdv', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.daterdv', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-1 ) );?>
            <?php
                echo $xform->input( 'Rendezvous.heurerdv', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.heurerdv', true ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5,  'empty' => true, 'hourRange' => array( 8, 19 ) ) );
            ?>
            <?php echo $form->input( 'Rendezvous.objetrdv', array( 'label' =>  ( __d( 'rendezvous', 'Rendezvous.objetrdv', true ) ), 'type' => 'text', 'rows' => 2, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.commentairerdv', array( 'label' =>  ( __d( 'rendezvous', 'Rendezvous.commentairerdv', true ) ), 'type' => 'text', 'rows' => 3, 'empty' => true ) );?>
        </fieldset>
    </div>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
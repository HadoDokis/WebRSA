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
            <legend>Détails PDO</legend>
            <?php /* if( $this->action == 'add' ){*/
                echo $form->input( 'Rendezvous.structurereferente_id', array( 'label' =>  ( __( 'lib_struct', true ) ), 'type' => 'select', 'options' => $struct, 'empty' => true ) );
//                 }
            ?>
            <?php echo $form->input( 'Rendezvous.statutrdv', array( 'label' =>  ( __( 'statutrdv', true ) ), 'type' => 'select', 'options' => $statutrdv, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.daterdv', array( 'label' =>  ( __( 'daterdv', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.objetrdv', array( 'label' =>  ( __( 'objetrdv', true ) ), 'type' => 'text', 'rows' => 2, 'empty' => true ) );?>
            <?php echo $form->input( 'Rendezvous.commentairerdv', array( 'label' =>  ( __( 'commentairerdv', true ) ), 'type' => 'text', 'rows' => 3, 'empty' => true ) );?>
        </fieldset>
    </div>

            <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
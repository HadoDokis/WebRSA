<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Ressources';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Déclaration de ressources';
    }
    else {
        $this->pageTitle = 'Édition de ressources';
    }
?>



<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Ressource', array(  'type' => 'post', 'url' => Router::url( null, true )  ));
//             echo '<div>';
//             echo $form->input( 'Ressource.id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Ressourcemensuelle.0.ressource_id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Ressourcemensuelle.1.ressource_id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Ressourcemensuelle.2.ressource_id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo '</div>';
        }
        else {
            echo $form->create( 'Ressource', array( 'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Ressource.id', array( 'type' => 'hidden' ) );
            echo '</div>';

            for( $i = 0 ; $i < 3 ; $i ++ ) {
                if( Set::extract( $this->data, 'Ressourcemensuelle.'.$i.'.id' ) !== null ) {
                    echo '<div>';
                    echo $form->input( 'Ressourcemensuelle.'.$i.'.id', array( 'type' => 'hidden' ) );
                    echo '</div>';
                    echo $form->input( 'Ressourcemensuelle.'.$i.'.ressource_id', array( 'type' => 'hidden' ) );
                    if( Set::extract( $this->data, 'Detailressourcemensuelle.'.$i.'.id' ) !== null ) {
                        echo $form->input( 'Detailressourcemensuelle.'.$i.'.id', array( 'type' => 'hidden' ) );
                        echo $form->input( 'Detailressourcemensuelle.'.$i.'.ressource_id', array( 'type' => 'hidden' ) );
                    }
                }
            }
        }
        echo '<div>';
        echo $form->input( 'Ressource.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
        echo '</div>';
    ?>

<?php include( '_form.ctp' ); ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
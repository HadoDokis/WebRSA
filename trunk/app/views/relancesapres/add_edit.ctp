<?php
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

    $this->pageTitle = 'Relance';

    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );

    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout relance';
    }
    else {
        $this->pageTitle = 'Édition relance';
    }

?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'PieceaprePieceapre',
                    'url' => Router::url( array( 'action' => 'ajaxpiece', Set::extract( $this->data, 'Relanceapre.apre_id' ) ), true )
                )
            );
        ?>
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>
    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $apre, 'Apre.id' ) ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Relanceapre.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <div class="aere">

    <fieldset>
        <?php
            $piecesManquantes = Set::extract( $apre, '/Relanceapre/Piecemanquante/libelle' );
            echo $xform->input( 'Relanceapre.daterelance', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
            echo $xform->input( 'Relanceapre.commentairerelance', array( 'domain' => 'apre' ) );
        ?>
    </fieldset>
    <fieldset>
        <legend>Pièces jointes manquantes</legend>
        <?php

            if( !empty( $piecesManquantes ) ) {
                echo '<ul><li>'.implode( '</li><li>', $piecesManquantes ).'</li></ul>';
            }
        ?>
    </fieldset>
    </div>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
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

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>
    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $apre, 'Apre.id' ) ) );
            echo $form->input( 'Relanceapre.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Relanceapre.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Relanceapre.personne_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <div class="aere">
        <fieldset>

            <?php
                echo $xform->input( 'Relanceapre.daterelance', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                echo $xform->enum( 'Relanceapre.etatdossierapre', array(  'domain' => 'apre', 'options' => $options['etatdossierapre'] ) );
                echo $xform->input( 'Relanceapre.commentairerelance', array( 'domain' => 'apre' ) );
            ?>
        </fieldset>
        <fieldset>
            <legend>Pièces jointes</legend>
            <?php
                $piecesPresentesId = Set::classicExtract( $apre, 'Pieceapre.{n}.id' );
                if( empty( $piecesPresentesId ) ) {
                    $piecesPresentesId = array_keys( $piecesapre );
                }
                echo $xform->input( 'Pieceapre.Pieceapre', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $piecesapre, 'value' => $piecesPresentesId ) );

            ?>
        </fieldset>
    </div>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
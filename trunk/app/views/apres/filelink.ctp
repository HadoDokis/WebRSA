<?php
    if( Configure::read( 'debug' ) > 0 ) {
        echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
        echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
        echo $javascript->link( 'fileuploader.js' );
    }
    $this->modelClass = $this->params['models'][0];
    $this->pageTitle =  __d( 'apre', "Apres::{$this->action}", true );
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personneId ) );

?>
<div class="with_treemenu">
    <?php
        echo $xhtml->tag( 'h1', $this->pageTitle );
        echo $form->create( 'Apre', array( 'type' => 'post', 'id' => 'apreform', 'url' => Router::url( null, true ) ) );
    ?>
        <fieldset>
    <legend><?php echo required( $default2->label( 'Apre.haspiecejointe' ) );?></legend>

    <?php echo $form->input( "{$this->modelClass}.haspiecejointe", array( 'type' => 'radio', 'options' => $options['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
    <fieldset id="filecontainer-piecejointe" class="noborder invisible">
        <?php
            echo $fileuploader->create(
                $fichiers,
                Router::url( array( 'action' => 'ajaxfileupload' ), true )
            );
        ?>
    </fieldset>
</fieldset>

<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        observeDisableFieldsetOnRadioValue(
            'apreform',
            'data[<?php echo $this->modelClass;?>][haspiecejointe]',
            $( 'filecontainer-piecejointe' ),
            '1',
            false,
            true
        );
    } );
</script>

<?php

        echo "<h2>Pièces déjà présentes</h2>";
        if( !empty( $apre['Fichiermodule'] ) ){
            $fichiersLies = Set::extract( $apre, 'Apre/Fichiermodule' );
            echo '<table class="aere"><tbody>';
                echo '<tr><th>Nom de la pièce jointe</th><th>Date d\'ajout</th><th>Action</th></tr>';
                if( isset( $fichiersLies ) ){
                    foreach( $fichiersLies as $i => $fichiers ){
                        echo '<tr><td>'.$fichiers['Fichiermodule']['name'].'</td>';
                        echo '<td>'.$locale->date( __( 'Locale->datetime', true ), $fichiers['Fichiermodule']['created'] ).'</td>';
                        echo '<td>'.$xhtml->link( 'Télécharger', array( 'action' => 'download', $fichiers['Fichiermodule']['id']    ) ).'</td></tr>';
                    }
                }
            echo '</tbody></table>';
        }
        else{
            echo '<p class="notice aere">Aucun élément.</p>';
        }
    ?>
</div>
    <div class="submit">
        <?php
            echo $form->submit( 'Enregistrer', array( 'div'=>false ) );
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $form->end();?>
<div class="clearer"><hr /></div>
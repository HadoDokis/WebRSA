<?php if( !empty( $piecetypecourrierpcg66 ) ): ?>
    <fieldset>
        <legend>Liste des pièces liées au type de courrier</legend>
        <table class="wide noborder">
            <tr>
                <td class="wide noborder">
                    <?php
                        foreach( $piecetypecourrierpcg66 as $id => $name ) {
                            echo $xform->input( "Piecetraitementpcg66.{$id}.id", array( 'type' => 'hidden' ) );
                            echo $xform->input( "Piecetraitementpcg66.{$id}.checked", array( 'label' => $name, 'type' => 'checkbox' ) );
                            echo $xform->input( "Piecetraitementpcg66.{$id}.piecetypecourrierpcg66_id", array( 'value' => $id, 'type' => 'hidden' ) );
                            echo $xform->input( "Piecetraitementpcg66.{$id}.commentaire", array( 'label' =>  '', 'type' => 'textarea' ) );
                        }
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
<?php else:?>
    <?php 
        echo '<p class="notice">Aucune pièce liée à ce type de courrier<p>';
    ?>
<?php endif;?>
<script type="text/javascript">
    <?php foreach( $piecetypecourrierpcg66 as $id => $name ) :?>
        observeDisableFieldsOnCheckbox(
            'Piecetraitementpcg66<?php echo $id;?>Checked',
            [
                'Piecetraitementpcg66<?php echo $id;?>Piecetypecourrierpcg66Id',
                'Piecetraitementpcg66<?php echo $id;?>Commentaire'
            ],
            false,
            true
        );
    <?php endforeach;?>
</script>
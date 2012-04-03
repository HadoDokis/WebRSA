<?php if( !empty( $modeletypecourrierpcg66 ) ): ?>
    <fieldset>
        <legend>Liste des modèles de courrier</legend>
        <table class="wide noborder">
            <tr>
                <td class="wide noborder">
                    <?php
                        foreach( $modeletypecourrierpcg66 as $id => $name ) {
                            echo $xform->input( "Modeletraitementpcg66.{$id}.id", array( 'type' => 'hidden' ) );
                            echo $xform->input( "Modeletraitementpcg66.{$id}.modeletypecourrierpcg66_id", array( 'value' => $id, 'type' => 'hidden' ) );
                            echo $xform->input( "Modeletraitementpcg66.{$id}.checked", array( 'label' => $name, 'type' => 'checkbox' ) );
                            
                            echo '<fieldset id="detailsmodelelie'.$id.'"><legend>Détails concernant le modèle lié &laquo; '.$name.' &raquo;</legend>';
								echo $xform->input( "Modeletraitementpcg66.{$id}.commentaire", array( 'label' =>  "Commentaire lié au modèle de courrier &laquo; {$name} &raquo;", 'type' => 'textarea' ) );
								echo '<fieldset id="listepieces'.$id.'"><legend>Liste des pièces liées au modèle de courrier &laquo; '.$name.' &raquo;</legend>';
									foreach( $listepieces[$id] as $idPiece => $namepiece ) {
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.id", array( 'type' => 'hidden' ) );
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.checked", array( 'label' => $namepiece, 'type' => 'checkbox' ) );
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.piecemodeletypecourrierpcg66_id", array( 'value' => $idPiece, 'type' => 'hidden' ) );
									}
								echo '</fieldset>';
							echo '</fieldset>';
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
    <?php foreach( array_keys( $modeletypecourrierpcg66 ) as $id ) :?>
		observeDisableFieldsetOnCheckbox(
			'Modeletraitementpcg66<?php echo $id;?>Checked',
			'detailsmodelelie<?php echo $id;?>',
			false,
			true
		);
        /*observeDisableFieldsOnCheckbox(
            'Modeletraitementpcg66<?php echo $id;?>Checked',
            [
                'Modeletraitementpcg66<?php echo $id;?>Piecemodeletypecourrierpcg66Id',
                'Modeletraitementpcg66<?php echo $id;?>Commentaire'
            ],
            false,
            true
        );*/
    <?php endforeach;?>
</script>
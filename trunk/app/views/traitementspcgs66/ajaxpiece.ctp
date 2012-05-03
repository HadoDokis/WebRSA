<?php if( !empty( $modeletypecourrierpcg66 ) ): ?>
    <fieldset>
        <legend>Liste des modèles de courrier</legend>

        <table class="wide noborder">
            <tr>
                <td class="wide noborder">
                    <?php
						/*
<input id="Modeletraitementpcg66Modeletypecourrierpcg66Id_" type="hidden" value="" name="data[Modeletraitementpcg66][modeletypecourrierpcg66_id]">
<input id="Modeletraitementpcg66Modeletypecourrierpcg66Id3" type="radio" value="3" name="data[Modeletraitementpcg66][modeletypecourrierpcg66_id]">
<label for="Modeletraitementpcg66Modeletypecourrierpcg66Id3">Auto inconnu de nos fichiers</label>
						*/
						echo '<div>';
						echo $xform->input( "Modeletraitementpcg66.id", array( 'type' => 'hidden' ) );
						echo $xform->input( "Modeletraitementpcg66.traitementpcg66_id", array( 'type' => 'hidden' ) );
						echo $xform->input( 'Modeletraitementpcg66.modeletypecourrierpcg66_id', array( 'type' => 'hidden', 'value' => '', 'id' => 'Modeletraitementpcg66Modeletypecourrierpcg66Id_' ) );
						echo '</div>';

                        foreach( $modeletypecourrierpcg66 as $id => $name ) {
							echo '<div>';
							echo $xform->singleRadioElement( "Modeletraitementpcg66.modeletypecourrierpcg66_id", $id, $name ).'<br/>';
                            echo '<fieldset id="detailsmodelelie'.$id.'"><legend>Détails concernant le modèle lié &laquo; '.$name.' &raquo;</legend>';
								// INFO: attention, on peut se le permettre car il n'y a pas de règle de validation sur le commentaire
								$value = '';
								if( $id == $this->data['Modeletraitementpcg66']['modeletypecourrierpcg66_id'] ) {
									if( isset( $this->data['Modeletraitementpcg66']['commentaire'] ) ) {
										$value = $this->data['Modeletraitementpcg66']['commentaire'];
									}
									else if( isset( $this->data['Modeletraitementpcg66'][$id]['commentaire'] ) ) {
										$value = $this->data['Modeletraitementpcg66'][$id]['commentaire'];
									}
								}

								echo $xform->input( "Modeletraitementpcg66.{$id}.commentaire", array(
									'label' =>  "Commentaire lié au modèle de courrier &laquo; {$name} &raquo;",
									'type' => 'textarea',
									'value' => $value
									)
								); // FIXME: nettoyer en js ?
								
								echo $xform->input( 'Piecemodeletypecourrierpcg66.Piecemodeletypecourrierpcg66', array( 'type' => 'select', 'multiple' => 'checkbox', 'options' => $listepieces[$id], 'id' => "listepieces{$id}", 'legend' => 'Liste des pièces liées au modèle de courrier &laquo; '.$name.' &raquo;' ) );
								/*echo '<fieldset id="listepieces'.$id.'"><legend>Liste des pièces liées au modèle de courrier &laquo; '.$name.' &raquo;</legend>';
									foreach( $listepieces[$id] as $idPiece => $namepiece ) {
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.id", array( 'type' => 'hidden' ) );
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.checked", array( 'label' => $namepiece, 'type' => 'checkbox' ) );
										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.piecemodeletypecourrierpcg66_id", array( 'value' => $idPiece, 'type' => 'hidden' ) );
									}
								echo '</fieldset>';*/
							echo '</fieldset>';

//                             echo $form->radio( "Modeletraitementpcg66.{$id}.modeletypecourrierpcg66_id", array( $id => $name ), array( 'value' => $id ), array() ) ;
//                             echo $xform->input( "Modeletraitementpcg66.{$id}.modeletypecourrierpcg66_id", array( 'value' => $id, 'type' => 'hidden' ) );
//                             echo $xform->input( "Modeletraitementpcg66.{$id}.checked", array( 'label' => $name, 'type' => 'checkbox' ) );
//                             
//                             echo '<fieldset id="detailsmodelelie'.$id.'"><legend>Détails concernant le modèle lié &laquo; '.$name.' &raquo;</legend>';
// 								echo $xform->input( "Modeletraitementpcg66.{$id}.commentaire", array( 'label' =>  "Commentaire lié au modèle de courrier &laquo; {$name} &raquo;", 'type' => 'textarea' ) );
// 								echo '<fieldset id="listepieces'.$id.'"><legend>Liste des pièces liées au modèle de courrier &laquo; '.$name.' &raquo;</legend>';
// 									foreach( $listepieces[$id] as $idPiece => $namepiece ) {
// 										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.id", array( 'type' => 'hidden' ) );
// 										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.checked", array( 'label' => $namepiece, 'type' => 'checkbox' ) );
// 										echo $xform->input( "Mtpcg66Pmtcpcg66.{$id}.{$idPiece}.piecemodeletypecourrierpcg66_id", array( 'value' => $idPiece, 'type' => 'hidden' ) );
// 									}
// 								echo '</fieldset>';
// 							echo '</fieldset>';
							echo '</div>';
                        }
// 						echo $xform->input( 'Modeletraitementpcg66.modeletypecourrierpcg66_id', array( 'label' => 'test', 'type' => 'radio', 'options' => $modeletypecourrierpcg66 ) );
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
		/*observeDisableFieldsetOnCheckbox(
			'Modeletraitementpcg66<?php echo $id;?>Checked',
			'detailsmodelelie<?php echo $id;?>',
			false,
			true
		);*/

		
		observeDisableFieldsetOnRadioValue(
			'traitementpcg66form',
			'data[Modeletraitementpcg66][modeletypecourrierpcg66_id]',
			$( 'detailsmodelelie<?php echo $id;?>' ),
			'<?php echo $id;?>',
			false,
			true
		);
		
		/*
				'traitementpcg66form',
				'data[Traitementpcg66][typetraitement]',
				$( 'filecontainer-courrier' ),
				'courrier',
				false,
				true*/

    <?php endforeach;?>
</script>
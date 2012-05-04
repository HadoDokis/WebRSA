<?php if( !empty( $modeletypecourrierpcg66 ) ): ?>
	<fieldset>
		<legend>Liste des modèles de courrier</legend>
<?php
/*	Configure::write( 'debug', 2);
	debug( $this->data );*/
	?>
		<table class="wide noborder">
			<tr>
				<td class="wide noborder">
					<?php
						echo '<div>';
						echo $xform->input( "Modeletraitementpcg66.id", array( 'type' => 'hidden' ) );
						echo $xform->input( "Modeletraitementpcg66.traitementpcg66_id", array( 'type' => 'hidden' ) );
						echo $xform->input( 'Modeletraitementpcg66.modeletypecourrierpcg66_id', array( 'type' => 'hidden', 'value' => '', 'id' => 'Modeletraitementpcg66Modeletypecourrierpcg66Id_' ) );
						echo '</div>';

						echo '<div id="Modeletraitementpcg66Modeletypecourrierpcg66Id" class="input radio">';
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
								
								echo $xform->input( 'Piecemodeletypecourrierpcg66.Piecemodeletypecourrierpcg66', array( 'type' => 'select', 'multiple' => 'checkbox', 'options' => $listepieces[$id], 'id' => "listepieces{$id}", 'label' => 'Liste des pièces liées au modèle de courrier &laquo; '.$name.' &raquo;' ) );
							echo '</fieldset>';
							echo '</div>';
						}
						echo '</div>';
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
		observeDisableFieldsetOnRadioValue(
			'traitementpcg66form',
			'data[Modeletraitementpcg66][modeletypecourrierpcg66_id]',
			$( 'detailsmodelelie<?php echo $id;?>' ),
			'<?php echo $id;?>',
			false,
			true
		);
	<?php endforeach;?>
</script>
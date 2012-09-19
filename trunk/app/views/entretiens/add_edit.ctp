<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
        <?php
            if( Configure::read( 'Cg.departement') == 66 ) {
                echo $ajax->remoteFunction(
                    array(
                        'update' => 'EntretienPartenaire',
                        'url' => Router::url( array( 'action' => 'ajaxaction', Set::extract( $this->data, 'Entretien.actioncandidat_id' ) ), true )
                    )
                );
            }
		?>;

		dependantSelect( 'EntretienReferentId', 'EntretienStructurereferenteId' );
		observeDisableFieldsetOnCheckbox( 'EntretienRendezvousprevu', $( 'EntretienRendezvousId' ).up( 'fieldset' ), false );
		dependantSelect( 'RendezvousReferentId', 'RendezvousStructurereferenteId' );
        
        

	});
</script>
<div class="with_treemenu">

	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}", true )
		);
	?>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Entretien', array( 'type' => 'post', 'url' => Router::url( null, true ),  'id' => 'Bilan' ) );
		}
		else {
			echo $form->create( 'Entretien', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Bilan' ) );
			echo '<div>';
			echo $form->input( 'Entretien.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $form->input( 'Entretien.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
		echo '</div>';
	?>

	<div class="aere">
		<fieldset class="aere">
				<?php
					echo $default->subform(
						array(
							'Entretien.structurereferente_id',
							'Entretien.referent_id',
							'Entretien.dateentretien' => array( 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false ),
							'Entretien.typeentretien' => array( 'required' => true, 'options' => $options['Entretien']['typeentretien'], 'empty' => true ),
							'Entretien.objetentretien_id' => array(  'empty' => true )
						),
						array(
							'options' => $options
						)
					);
				?>
            <?php if( Configure::read( 'Cg.departement') == 66 ):?>
                <fieldset class="invisible">
                    <legend><strong>Positionnement éventuel sur une action d'insertion</strong></legend>
                    <table class="wide noborder">
                        <tr>
                            <td class="noborder">
                                <?php
                                    echo $form->input( 'Entretien.actioncandidat_id', array( 'label' => 'Intitulé de l\'action', 'type' => 'select', 'options' => $actionsSansFiche, 'empty' => true ) );                 
                                    echo $ajax->observeField( 'EntretienActioncandidatId', array( 'update' => 'EntretienPartenaire', 'url' => Router::url( array( 'action' => 'ajaxaction' ), true ) ) );
                                    echo $xhtml->tag(
                                        'div',
                                        ' ',
                                        array(
                                            'id' => 'EntretienPartenaire'
                                        )
                                    );
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            <?php endif;?>
            
            
				<?php
                    echo $default->subform(
                        array(
                            'Entretien.commentaireentretien'
                        ),
                        array(
                            'options' => $options
                        )
                    );
                    echo $xform->input( 'Entretien.arevoirle', array( 'label' => 'A revoir le ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date('Y')+2, 'minYear' => date('Y')-2, 'empty' => true ) );?>
				<?php if( Configure::read( 'Cg.departement' ) != 66):?>
				<?
					echo $xform->input( 'Entretien.rendezvousprevu', array( 'label' => 'Rendez-vous prévu', 'type' => 'checkbox' ) );
				?>
			<fieldset class="invisible" id="rendezvousprevu">
				<?php
					echo $default->subform(
						array(
							'Entretien.rendezvous_id' => array( 'type' => 'hidden' ),
							'Rendezvous.id' => array( 'type' => 'hidden' ),
							'Rendezvous.personne_id' => array( 'value' => $personne_id, 'type' => 'hidden' ),
							'Rendezvous.daterdv' => array( 'label' =>  'Rendez-vous fixé le ', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true ),
							'Rendezvous.heurerdv' => array( 'label' => 'A ', 'type' => 'time', 'timeFormat' => '24', 'minuteInterval' => 5,  'empty' => true, 'hourRange' => array( 8, 19 ) ),
							'Rendezvous.typerdv_id' => array( 'label' => 'Type de rdv', 'type' => 'select', 'options' => $typerdv, 'empty' => true ),
						),
						array(
							'options' => $options
						)
					);

					echo $xform->input( 'Rendezvous.structurereferente_id', array( 'label' =>  required( __( 'Nom de l\'organisme', true ) ), 'type' => 'select', 'options' => $structs, 'empty' => true ) );

					echo $xform->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom de l\'agent / du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) );
				?>
			</fieldset>
			<?php endif;?>
		</fieldset>

	</div>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
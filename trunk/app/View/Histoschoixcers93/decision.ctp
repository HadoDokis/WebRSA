<?php
	if( $this->action == 'attdecisioncpdv' ) {
		$title_for_layout = 'Décison du Référent';
	}
	else {
		$title_for_layout = 'Décison du Responsable';
	}
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		// FIXME: liste de titres depuis le contrôleur
		echo $this->Html->tag( 'h1', $title_for_layout );
	?>
	<br />
	<div id="tabbedWrapper" class="tabs">
		<div id="decisioncg">
			<h2 class="title">Décision CG</h2>
			<?php

				echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'histochoixcer93' ) ) );

				echo $this->Xform->inputs(
					array(
						'fieldset' => false,
						'legend' => false,
						'Histochoixcer93.id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.cer93_id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.user_id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.formeci' => array( 'type' => 'radio', 'options' => $options['Cer93']['formeci'] ),
						'Histochoixcer93.etape' => array( 'type' => 'hidden' )
					)
				);

				$checkedCommentaires = Set::filter( Set::extract( $this->request->data, '/Commentairenormecer93/Commentairenormecer93/commentairenormecer93_id' ) );
				echo '<fieldset><legend>Commentaires</legend>';
				if( !empty( $options['Commentairenormecer93']['commentairenormecer93_id'] ) ) {
					$i = 0;
					foreach( $options['Commentairenormecer93']['commentairenormecer93_id'] as $id => $commentaireName ) {
						$array_key = array_search( $id, $checkedCommentaires );
						$checked = ( ( $array_key !== false ) ? 'checked' : '' );

						echo $this->Xform->input( "Commentairenormecer93.Commentairenormecer93.{$i}.commentairenormecer93_id", array( 'name' => "data[Commentairenormecer93][Commentairenormecer93][{$i}][commentairenormecer93_id]", 'label' => $commentaireName, 'type' => 'checkbox', 'value' => $id, 'checked' => $checked, 'hiddenField' => false ) );
						
						if( $id == $commentairenormecer93_isautre_id) {
							echo $this->Xform->input( "Commentairenormecer93.Commentairenormecer93.{$i}.commentaireautre", array( 'name' => "data[Commentairenormecer93][Commentairenormecer93][{$i}][commentaireautre]", 'label' => false, 'type' => 'textarea', 'value' => Hash::get( $this->request->data, "Commentairenormecer93.Commentairenormecer93.{$array_key}.commentaireautre" ) ) );
						}
						$i++;
					}
				}
				echo '</fieldset>';

				echo $this->Xform->inputs(
					array(
						'fieldset' => false,
						'legend' => false,
						'Histochoixcer93.datechoix' => array( 'type' => 'date', 'dateFormat' => 'DMY' )
					)
				);

				if( $this->action == 'attdecisioncg' ) {
					echo $this->Xform->input( 'Histochoixcer93.isrejet', array( 'type' => 'checkbox' ) );
				}
			?>
			<?php
				echo $this->Html->tag(
					'div',
					$this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
					.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
					array( 'class' => 'submit noprint' )
				);

				echo $this->Xform->end();
			?>
		</div>
		<div id="cerview">
			<h2 class="title">Visualisation du CER</h2>
			<?php
				include( dirname( __FILE__ ).'/../Cers93/_view.ctp' );
			?>
		</div>
	</div>
</div>
<div class="clearer"><hr /></div>
<script type="text/javascript">
//<![CDATA[
	<?php foreach( $options['Commentairenormecer93']['commentairenormecer93_id'] as $key => $commentairename ) :?>
		observeDisableFieldsOnCheckbox(
			'Commentairenormecer93Commentairenormecer93<?php echo $key;?>Commentairenormecer93Id',
			['Commentairenormecer93Commentairenormecer93<?php echo $key;?>Commentaireautre'],
			false,
			true
		);

	<?php endforeach;?>
//]]>
</script>
<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
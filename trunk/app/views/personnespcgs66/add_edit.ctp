<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyerId ) );
?>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect(
                'Personnepcg66Categoriedetail',
                'Personnepcg66Categoriegeneral'
            );
	});
</script>

<div class="with_treemenu">
	<?php

		$chargeDossier = Set::classicExtract( $gestionnaire, Set::classicExtract( $dossierpcg66, 'Dossierpcg66.user_id' ) );
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'personnepcg66', "Personnespcgs66::{$this->action}", true ).' géré par '.$chargeDossier
		);

		echo $xform->create( 'Personnepcg66', array( 'id' => 'personnepcg66form' ) );
		if( Set::check( $this->data, 'Personnepcg66.id' ) ){
			echo $xform->input( 'Personnepcg66.id', array( 'type' => 'hidden' ) );
		}

		echo $default2->subform(
			array(
				'Personnepcg66.dossierpcg66_id' => array( 'type' => 'hidden', 'value' => $dossierpcg66Id ),
				'Personnepcg66.personne_id' => array( 'type' => 'select', 'empty' => true, 'options' => $personnes, 'required' => true ),
				'Personnepcg66.user_id' => array( 'type' => 'hidden', 'value' => $userConnected ),
			),
			array(
				'options' => $options
			)
		);
	?>
	<table class="noborder" id="infosPdo">
		<tr>
			<td class="mediumSize noborder">
				<?php $validationErrorPersonnepcg66SituationpdoSituationpdo = ( isset( $this->validationErrors['Personnepcg66']['Situationpdo.Situationpdo'] ) && !empty( $this->validationErrors['Personnepcg66']['Situationpdo.Situationpdo'] ) );?>
				<div class="input checkboxes<?php if( $validationErrorPersonnepcg66SituationpdoSituationpdo ) { echo ' error'; }?>">
					<fieldset>
						<legend><?php echo required( $default2->label( 'Situationpdo.Situationpdo', array( 'domain' => 'personnepcg66' ) ) );?></legend>
						<?php
							if( $validationErrorPersonnepcg66SituationpdoSituationpdo ) {
								echo "<div class='error-message'>".$this->validationErrors['Personnepcg66']['Situationpdo.Situationpdo']."</div>";
							}
							echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => required( 'Motif de la décision' ), 'multiple' => 'checkbox' , 'options' => $situationlist, 'fieldset' => false ) );
						?>
					</fieldset>
				</div>
			</td>
			<td class="mediumSize noborder">
				<?php $validationErrorPersonnepcg66SituationpdoStatutpdo = ( isset( $this->validationErrors['Personnepcg66']['Statutpdo.Statutpdo'] ) && !empty( $this->validationErrors['Personnepcg66']['Statutpdo.Statutpdo'] ) );?>
				<div class="input checkboxes<?php if( $validationErrorPersonnepcg66SituationpdoStatutpdo ) { echo ' error'; }?>">
					<fieldset>
						<legend><?php echo required( $default2->label( 'Statutpdo.Statutpdo', array( 'domain' => 'personnepcg66' ) ) );?></legend>
						<?php
							if ( isset( $this->validationErrors['Personnepcg66']['Statutpdo.Statutpdo'] ) && !empty( $this->validationErrors['Personnepcg66']['Statutpdo.Statutpdo'] ) ) {
								echo "<div class='error-message'>".$this->validationErrors['Personnepcg66']['Statutpdo.Statutpdo']."</div>";
							}
							echo $xform->input( 'Statutpdo.Statutpdo', array( 'type' => 'select', 'label' => required( 'Statut de la personne' ), 'multiple' => 'checkbox' , 'options' => $statutlist, 'fieldset' => false  ) );                 
						?>
					</fieldset>
				</div>
			</td>
		</tr>
	</table>
	<?php
		echo $xhtml->tag(
			'p',
			'Catégories : '
		);

		$selected = null;
		if( $this->action == 'edit' ){
			$selected = preg_replace( '/^[^_]+_/', '', $this->data['Personnepcg66']['categoriegeneral'] ).'_'.$this->data['Personnepcg66']['categoriedetail'];
		}

		echo $default->subform(
			array(
				'Personnepcg66.categoriegeneral' => array( 'label' => __d( 'personnepcg66', 'Personnepcg66.categoriegeneral', true ), 'type' => 'select', 'empty' => true, 'options' => $options['Coderomesecteurdsp66'] ),
				'Personnepcg66.categoriedetail' => array( 'label' => __d( 'personnepcg66', 'Personnepcg66.categoriedetail', true ), 'type' => 'select', 'empty' => true, 'options' => $options['Coderomemetierdsp66'], 'selected' => $selected )
			),
			array(
				'options' => $options
			)
		);

		echo "<div class='submit'>";
			echo $form->submit( 'Enregistrer', array( 'div'=>false ) );
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div'=>false ) );
		echo "</div>";

		echo $form->end();
	?>
</div>
<div class="clearer"><hr /></div>
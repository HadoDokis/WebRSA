<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
	);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	echo $xform->create( null, array( 'id' => 'ActioncandidatAddEditForm' ) );

	if (isset($this->data['Actioncandidat']['id']))
		echo $form->input('Actioncandidat.id', array('type'=>'hidden'));

	echo $default2->subform(
		array(
			'Actioncandidat.name' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.themecode' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.codefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.numcodefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.hasfichecandidature' => array( 'domain' => 'actioncandidat', 'required' => true, 'type'=>'radio', 'options' => $options['Actioncandidat']['hasfichecandidature'] ),
			'Actioncandidat.actif' => array( 'label' => 'Active ?', 'type' => 'radio', 'options' => $options['Actioncandidat']['actif'] ),
			'Actioncandidat.modele_document' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.modele_document', true ), 'required' => true, 'value' => isset( $this->data['Actioncandidat']['modele_document'] ) ? $this->data['Actioncandidat']['modele_document'] : 'fichecandidature' )
		)
	);
?>
<fieldset>
	<legend><?php echo required( $default2->label( 'Actioncandidat.haspiecejointe' ) );?></legend>

	<?php echo $form->input( 'Actioncandidat.haspiecejointe', array( 'type' => 'radio', 'options' => $options['Actioncandidat']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
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
			'ActioncandidatAddEditForm',
			'data[Actioncandidat][haspiecejointe]',
			$( 'filecontainer-piecejointe' ),
			'1',
			false,
			true
		);
	} );
</script>

<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			observeDisableFieldsetOnCheckbox( 'ActioncandidatCorrespondantaction', 'filtre_referent', false );

			observeDisableFieldsOnRadioValue(
				'ActioncandidatAddEditForm',
				'data[Actioncandidat][hasfichecandidature]',
				[
					'ActioncandidatNbpostedispo',
					'ActioncandidatNbposterestant'
				],
				1,
				true
			);

			observeDisableFieldsetOnRadioValue(
				'ActioncandidatAddEditForm',
				'data[Actioncandidat][hasfichecandidature]',
				$( 'avecfichecandidature' ),
				1,
				false,
				true
			);

			observeDisableFieldsetOnRadioValue(
				'ActioncandidatAddEditForm',
				'data[Actioncandidat][typeaction]',
				$( 'nbposte' ),
				'poste',
				false,
				true
			);

			observeDisableFieldsetOnRadioValue(
				'ActioncandidatAddEditForm',
				'data[Actioncandidat][typeaction]',
				$( 'nbheure' ),
				'heure',
				false,
				true
			);
		} );
	</script>
	<fieldset class="invisible" id="avecfichecandidature">
			<?php
				echo $default->subform(
					array(
						'Actioncandidat.correspondantaction' => array('type' => 'checkbox' )
					)
				);
			?>
			<fieldset class="col2" id="filtre_referent">
				<legend>Référent</legend>
				<?php
					echo $default->subform(
						array(
							'Actioncandidat.referent_id' => array('domain' => 'actioncandidat', 'type'=>'select' ),
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>
		<?php

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				echo $default->subform(
					array(
						'Actioncandidat.chargeinsertion_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
						'Actioncandidat.secretaire_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
						'Actioncandidat.contractualisation' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
						'Actioncandidat.lieuaction' => array( 'domain' => 'actioncandidat', 'required' => true ),
						'Actioncandidat.cantonaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'options' => $cantons )
					),
					array(
						'options' => $options
					)
				);
			}
			else {
				echo $default->subform(
					array(
						'Actioncandidat.chargeinsertion_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
						'Actioncandidat.secretaire_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
						'Actioncandidat.contractualisation93' => array( 'domain' => 'actioncandidat', 'required' => true ),
						'Actioncandidat.lieuaction' => array( 'domain' => 'actioncandidat', 'required' => true ),
						'Actioncandidat.cantonaction' => array( 'domain' => 'actioncandidat', 'required' => true )
					),
					array(
						'options' => $options
					)
				);

			}

			echo $default->subform(
				array(
					'Actioncandidat.ddaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 5 ),
					'Actioncandidat.dfaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 5 )
				),
				array(
					'options' => $options
				)
			);
		?>
		<?php
			echo $default->subform(
				array(
					'Actioncandidat.typeaction' => array( 'domain' => 'actioncandidat', 'type' => 'radio', 'options' => $options['Actioncandidat']['typeaction'], 'required' => true )
				),
				array(
					'options' => $options
				)
			);
		?>
		<fieldset id="nbposte">
			<?php
				echo $default->subform(
					array(
						'Actioncandidat.nbpostedispo' => array( 'domain' => 'actioncandidat', 'required' => true ),
						'Actioncandidat.nbposterestant' => array( 'domain' => 'actioncandidat')
						),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
		<fieldset id="nbheure">
			<?php
				echo $default->subform(
					array(
						'Actioncandidat.nbheuredispo' => array( 'type' => 'text', 'domain' => 'actioncandidat', 'required' => true ),
						'Actioncandidat.nbheurerestante' => array( 'type' => 'text', 'domain' => 'actioncandidat')
						),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
		<fieldset class="col2">
			<legend>Zones géographiques</legend>
			<script type="text/javascript">
				document.observe( "dom:loaded", function() {
					observeDisableFieldsetOnCheckbox( 'ActioncandidatFiltreZoneGeo', 'filtres_zone_geo', false );
				} );
			</script>
			<?php echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>
			<?php echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>

			<?php
				echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $options['Zonegeographique'] ) );
			?>
		</fieldset>
		<?php
			echo $default->subform(
				array(
					'Actioncandidat.contactpartenaire_id' => array( 'type' => 'select', 'empty' => true, 'required' => true )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
    <fieldset class="invisible">
    <?php
        echo $default2->subform(
            array(
                'Motifsortie.Motifsortie' => array( 'multiple' => 'checkbox', 'empty' => false )
            ),
            array(
                'options' => $motifssortie
            )
        );
    ?>
</fieldset>
<?php
	echo $xform->end( __( 'Save', true ) );
	echo $default->button(
		'back',
		array('controller' => 'actionscandidats', 'action' => 'index'),
		array('id' => 'Back')
	);
?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		var v = $( 'ActioncandidatAddEditForm' ).getInputs( 'radio', 'data[Actioncandidat][hasfichecandidature]' );
		var currentSelectValue = $F('ActioncandidatContractualisation');
		$( v ).each( function( radio ) {
			$( radio ).observe( 'change', function( event ) {
				if( radio.value == 0 ){
					$( 'ActioncandidatContractualisation' ).setValue('internecg');
				}
				else{
					$( 'ActioncandidatContractualisation' ).setValue(currentSelectValue);
				}
			} );
		} );
	} );
</script>
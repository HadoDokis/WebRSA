<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
	);

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<?php
	echo $xform->create( null, array( 'id' => 'ActioncandidatAddEditForm' ) );
	
	if (isset($this->data['Actioncandidat']['id']))
		echo $form->input('Actioncandidat.id', array('type'=>'hidden'));	

	echo $default->subform(
		array(
			'Actioncandidat.name' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.themecode' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.codefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.numcodefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.hasfichecandidature' => array( 'domain' => 'actioncandidat', 'required' => true, 'type'=>'radio', 'options' => $options['Actioncandidat']['hasfichecandidature'] )
		)
	);

?>
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
			echo $default->subform(
				array(
					'Actioncandidat.chargeinsertion_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
					'Actioncandidat.secretaire_id' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
					'Actioncandidat.contractualisation' => array( 'domain' => 'actioncandidat', 'required' => true, 'type' => 'select'),
					'Actioncandidat.lieuaction' => array( 'domain' => 'actioncandidat', 'required' => true ),
					'Actioncandidat.cantonaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'options' => $cantons ),
					'Actioncandidat.ddaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 5 ),
					'Actioncandidat.dfaction' => array( 'domain' => 'actioncandidat', 'required' => true, 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 5 ),
					'Actioncandidat.nbpostedispo' => array( 'domain' => 'actioncandidat', 'required' => true ),
					'Actioncandidat.nbposterestant' => array( 'domain' => 'actioncandidat')
				),
				array(
					'options' => $options
				)
			);
		?>
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
				$selected = array();
				if( $this->action == 'add' ){
					$selected = $zonesselected;
				}
				else if( $this->action == 'edit' ){
					$selected = $this->data['Zonegeographique']['Zonegeographique'];
				}
				echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $options['Zonegeographique'], 'selected' => $selected ) );
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
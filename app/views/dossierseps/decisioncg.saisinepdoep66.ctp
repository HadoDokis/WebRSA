<?php
	$domain = 'pdo';
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>
<fieldset id="Decision" class="invisible">
	<?php
		echo $form->create('Dossierep', array('url'=>'/dossierseps/decisioncg/'.$dossierep_id, 'id'=>'DossierepDecisioncg'));

		if (isset($this->data['Decisionsaisinepdoep66']['id']))
			echo $form->input('Decisionsaisinepdoep66.id', array('type'=>'hidden'));

		echo $form->input('Decisionsaisinepdoep66.passagecommissionep_id', array('type'=>'hidden'));
		echo $form->input('Decisionsaisinepdoep66.etape', array('type'=>'hidden', 'value'=>'cg'));
		echo $form->input('Saisinepdoep66.dossierep_id', array('type'=>'hidden', 'value' => $dossierep_id ));

		echo $default->subform(
			array(
				'Decisionsaisinepdoep66.decision' => array( 'label' =>  ( __( 'État du dossier', true ) ), 'type' => 'select', 'empty' => true ),
				'Decisionsaisinepdoep66.raisonnonpassage' => array( 'label' =>  'Raison : ', 'type' => 'textarea' ),
				'Decisionsaisinepdoep66.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
				'Decisionsaisinepdoep66.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
				//'Decisionsaisinepdoep66.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
				//'Decisionsaisinepdoep66.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
		<!--<fieldset id="nonadmis" class="invisible">
			<?php
				echo $default->subform(
					array(
						'Decisionsaisinepdoep66.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>-->
	<?php
		echo $default->subform(
			array(
				'Decisionsaisinepdoep66.commentaire' => array( 'label' =>  'Observation : ', 'type' => 'textarea'/*, 'rows' => 3*/ ),
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);

		echo $form->end('Enregistrer');
	?>
</fieldset>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		$( 'Decisionsaisinepdoep66Decision' ).observe( 'change', function() {
			afficheRaisonpassage();
		} );
		afficheRaisonpassage();
	});
	
	function afficheRaisonpassage() {
		if ( $F( 'Decisionsaisinepdoep66Decision' ) == 'annule' || $F( 'Decisionsaisinepdoep66Decision' ) == 'reporte' ) {
			$( 'Decisionsaisinepdoep66DatedecisionpdoDay' ).disable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoMonth' ).disable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoYear' ).disable();
			$( 'Decisionsaisinepdoep66DecisionpdoId' ).disable();
			$( 'Decisionsaisinepdoep66Commentaire' ).disable();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).up(0).show();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).enable();
		}
		else if ( $F( 'Decisionsaisinepdoep66Decision' ) == '' ) {
			$( 'Decisionsaisinepdoep66DatedecisionpdoDay' ).disable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoMonth' ).disable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoYear' ).disable();
			$( 'Decisionsaisinepdoep66DecisionpdoId' ).disable();
			$( 'Decisionsaisinepdoep66Commentaire' ).disable();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).up(0).hide();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).disable();
		}
		else {
			$( 'Decisionsaisinepdoep66DatedecisionpdoDay' ).enable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoMonth' ).enable();
			$( 'Decisionsaisinepdoep66DatedecisionpdoYear' ).enable();
			$( 'Decisionsaisinepdoep66DecisionpdoId' ).enable();
			$( 'Decisionsaisinepdoep66Commentaire' ).enable();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).up(0).hide();
			$( 'Decisionsaisinepdoep66Raisonnonpassage' ).disable();
		}
	}
</script>
<?php
	// CSS
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s', true ),
		Set::extract( $dsp, 'Personne.qual' ).' '.Set::extract( $dsp, 'Personne.nom' ).' '.Set::extract( $dsp, 'Personne.prenom' )
	);

	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::extract( $dsp, 'Personne.id' ) ) );

	$dsp_id = Set::classicExtract( $this->data, 'Dsp.id' );
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue( 'DspTopdrorsarmiant', [ 'DspDrorsarmianta2' ], '1', false );
		observeDisableFieldsOnValue( 'DspAccosocfam', [ 'DspLibcooraccosocfam' ], 'O', false );
		observeDisableFieldsOnValue( 'DspAccosocindi', [ 'DspLibcooraccosocindi' ], 'O', false );
		observeDisableFieldsOnValue( 'DspTopqualipro', [ 'DspLibautrqualipro' ], '1', false );
		observeDisableFieldsOnValue( 'DspTopcompeextrapro', [ 'DspLibcompeextrapro' ], '1', false );
		observeDisableFieldsOnValue( 'DspHispro', [ 'DspLibderact', 'DspLibsecactderact', 'DspCessderact', 'DspTopdomideract', 'DspLibactdomi', 'DspLibsecactdomi', 'DspDuractdomi' ], '1904', true );
		observeDisableFieldsOnValue( 'DspAccoemploi', [ 'DspLibcooraccoemploi' ], [ '1802', '1803' ], false );
		observeDisableFieldsOnValue( 'DspTopautrpermicondu', [ 'DspLibautrpermicondu' ], '1', false );
		// FIXME: pas de niveau = pas de diplôme ?
		observeDisableFieldsOnValue( 'DspNivetu', [ 'DspNivdipmaxobt', 'DspAnnobtnivdipmax' ], [ '', '1207' ], true );
		
		observeDisableFieldsOnValue( 'DspTopmoyloco', [ 'Detailmoytrans0Moytrans', 'Detailmoytrans1Moytrans', 'Detailmoytrans2Moytrans', 'Detailmoytrans3Moytrans', 'Detailmoytrans4Moytrans', 'Detailmoytrans5Moytrans', 'Detailmoytrans6Moytrans', 'Detailmoytrans7Moytrans' ], '1', false );
		
		observeDisableFieldsOnValue( 'DspTopprojpro', [ 'Detailprojpro0Projpro', 'Detailprojpro1Projpro', 'Detailprojpro2Projpro', 'Detailprojpro3Projpro', 'Detailprojpro4Projpro', 'Detailprojpro5Projpro', 'Detailprojpro6Projpro', 'Detailprojpro7Projpro', 'Detailprojpro8Projpro', 'Detailprojpro9Projpro', 'Detailprojpro10Projpro', 'Detailprojpro11Projpro', 'Detailprojpro12Projpro' ], '1', false );

		observeDisableFieldsOnCheckbox( 'Detailnatmob0Natmob', [ 'Detailnatmob1Natmob', 'Detailnatmob2Natmob', 'Detailnatmob3Natmob' ], true );
		observeDisableFieldsOnCheckbox( 'Detaildifdisp0Difdisp', [ 'Detaildifdisp1Difdisp', 'Detaildifdisp2Difdisp', 'Detaildifdisp3Difdisp', 'Detaildifdisp4Difdisp', 'Detaildifdisp5Difdisp' ], true );
		observeDisableFieldsOnCheckbox( 'Detaildifsoc0Difsoc', [ 'Detaildifsoc1Difsoc', 'Detaildifsoc2Difsoc', 'Detaildifsoc3Difsoc', 'Detaildifsoc4Difsoc', 'Detaildifsoc5Difsoc', 'Detaildifsoc6Difsoc', 'Detaildifsoc6Libautrdifsoc' ], true );
		observeDisableFieldsOnCheckbox( 'Detaildiflog0Diflog', [ 'Detaildiflog1Diflog', 'Detaildiflog2Diflog', 'Detaildiflog3Diflog', 'Detaildiflog4Diflog', 'Detaildiflog5Diflog', 'Detaildiflog6Diflog', 'Detaildiflog7Diflog', 'Detaildiflog8Diflog', 'Detaildiflog8Libautrdiflog' ], true );
	} );
</script>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );

		// Formulaire
		echo $xform->create( null );

		// FIXME: id / personne_id
		$tmp = '';
		if( !empty( $this->data['Dsp']['id'] ) ) {
			$tmp .= $xform->input( 'Dsp.id', array( 'type' => 'hidden' ) );
		}
		$tmp .= $xform->input( 'Dsp.personne_id', array( 'type' => 'hidden', 'value' => Set::extract( $dsp, 'Personne.id' ) ) );
		echo $xhtml->tag( 'div', $tmp );

// 		asort( $options['sitpersdemrsa'] );
/*
Plan:
	- GeneraliteDSPP
	- SituationSociale
		* CommunSituationSociale
		* DetailDifficulteSituationSociale (0-n)
		* DetailAccompagnementSocialFamilial (0-n)
		* DetailAccompagnementSocialIndividuel (0-n)
		* DetailDifficulteDisponibilite (0-n)
	- NiveauEtude
	- DisponibiliteEmploi
	- SituationProfessionnelle
	- Mobilite
		* CommunMobilite
		* DetailMobilite (0-n)
	- DifficulteLogement
		* CommunDifficulteLogement
		* DetailDifficulteLogement (0-n)
*/
	?>
	<fieldset>
		<legend>Généralités</legend>
		<?php
			echo $default->subform(
				array(
					'Dsp.sitpersdemrsa',
					'Dsp.topisogroouenf',
					'Dsp.topdrorsarmiant',
					'Dsp.drorsarmianta2',
					'Dsp.topcouvsoc'
				),
				array(
					'options' => $options
				)
			);
			/*echo $xform->enum( 'Dsp.sitpersdemrsa', array( 'options' => $options['sitpersdemrsa'] ) );
			echo $xform->enum( 'Dsp.topisogroouenf', array( 'options' => $options['topisogroouenf'] ) );
			echo $xform->enum( 'Dsp.topdrorsarmiant', array( 'options' => $options['topdrorsarmiant'] ) );
			echo $xform->enum( 'Dsp.drorsarmianta2', array( 'options' => $options['drorsarmianta2'] ) );
			echo $xform->enum( 'Dsp.topcouvsoc', array( 'options' => $options['topcouvsoc'] ) );*/
		?>
	</fieldset>

	<fieldset>
		<legend>Situation sociale</legend>
		<fieldset>
			<legend>Généralités</legend>
			<?php
				echo $default->subform(
					array(
						'Dsp.accosocfam',
						'Dsp.libcooraccosocfam' => array( 'type' => 'textarea' ),
						'Dsp.accosocindi',
						'Dsp.libcooraccosocindi' => array( 'type' => 'textarea' ),
						'Dsp.soutdemarsoc'
					),
					array(
						'options' => $options
					)
				);
				/*echo $xform->enum( 'Dsp.accosocfam', array( 'options' => $options['accosocfam'] ) );
				echo $xform->input( 'Dsp.libcooraccosocfam', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
				echo $xform->enum( 'Dsp.accosocindi', array( 'options' => $options['accosocindi'] ) );
				echo $xform->input( 'Dsp.libcooraccosocindi', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
				echo $xform->enum( 'Dsp.soutdemarsoc', array( 'options' => $options['soutdemarsoc'] ) );*/
			?>
		</fieldset>

		<?php
			// SituationSociale - DetailDifficulteSituationSociale (0-n)
			echo $dsphm->fieldset( 'Detaildifsoc', 'difsoc', 'libautrdifsoc', $dsp_id, '0407', $options['Detaildifsoc']['difsoc'] );
			
			// SituationSociale - DetailDifficulteSituationSocialeProfessionel (0-n)
			if ($cg=='cg58')
				echo $dsphm->fieldset( 'Detaildifsocpro', 'difsocpro', 'libautrdifsocpro', $dsp_id, '2110', $options['Detaildifsocpro']['difsocpro'] );

			// SituationSociale - DetailAccompagnementSocialFamilial (0-n)
			echo $dsphm->fieldset( 'Detailaccosocfam', 'nataccosocfam', 'libautraccosocfam', $dsp_id, '0413', $options['Detailaccosocfam']['nataccosocfam'] );

			// SituationSociale - DetailAccompagnementSocialIndividuel (0-n)
			echo $dsphm->fieldset( 'Detailaccosocindi', 'nataccosocindi', 'libautraccosocindi', $dsp_id, '0420', $options['Detailaccosocindi']['nataccosocindi'] );

			// SituationSociale - DetailDifficulteDisponibilite (0-n)
			echo $dsphm->fieldset( 'Detaildifdisp', 'difdisp', null, $dsp_id, null, $options['Detaildifdisp']['difdisp'] );
		?>
	</fieldset>

	<fieldset>
		<legend>Niveau d'étude</legend>
		<?php
			echo $default->subform(
				array(
					'Dsp.nivetu',
					'Dsp.nivdipmaxobt',
					'Dsp.annobtnivdipmax',
					'Dsp.topqualipro',
					'Dsp.libautrqualipro',
					'Dsp.topcompeextrapro',
					'Dsp.libcompeextrapro'
				),
				array(
					'options' => $options
				)
			);
			/*echo $xform->enum( 'Dsp.nivetu', array( 'options' => $options['nivetu'] ) );
			echo $xform->enum( 'Dsp.nivdipmaxobt', array( 'options' => $options['nivdipmaxobt'] ) );
			echo $xform->input( 'Dsp.annobtnivdipmax', array( 'domain' => 'dsp', 'type' => 'select', 'options' => array_range( date( 'Y' ), 1900 ), 'empty' => '' ) );
			echo $xform->enum( 'Dsp.topqualipro', array( 'options' => $options['topqualipro'] ) );
			echo $xform->input( 'Dsp.libautrqualipro', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
			echo $xform->enum( 'Dsp.topcompeextrapro', array( 'options' => $options['topcompeextrapro'] ) );
			echo $xform->input( 'Dsp.libcompeextrapro', array( 'domain' => 'dsp', 'type' => 'textarea' ) );*/
		?>
	</fieldset>

	<fieldset>
		<legend>Disponibilités emploi</legend>
		<?php
			echo $default->subform(
				array(
					'Dsp.topengdemarechemploi'
				),
				array(
					'options' => $options
				)
			);
// 			echo $xform->enum( 'Dsp.topengdemarechemploi', array( 'options' => $options['topengdemarechemploi'] ) );
		?>
	</fieldset>

	<fieldset>
		<legend>Situation professionnelle</legend>
		<?php
			echo $default->subform(
				array(
					'Dsp.hispro',
					'Dsp.libderact',
					'Dsp.libsecactderact',
					'Dsp.cessderact',
					'Dsp.topdomideract',
					'Dsp.libactdomi',
					'Dsp.libsecactdomi',
					'Dsp.duractdomi',
					'Dsp.inscdememploi',
					'Dsp.topisogrorechemploi',
					'Dsp.accoemploi',
					'Dsp.libcooraccoemploi' => array( 'type' => 'textarea' ),
					'Dsp.topprojpro'
				),
				array(
					'options' => $options
				)
			);
				
			if ($cg=='cg58')
				echo $dsphm->fieldset( 'Detailprojpro', 'projpro', 'libautrprojpro', $dsp_id, '2213', $options['Detailprojpro']['projpro'] );
			
			echo $default->subform(
				array(
					'Dsp.libemploirech',
					'Dsp.libsecactrech',
					'Dsp.topcreareprientre',
					'Dsp.concoformqualiemploi'
				),
				array(
					'options' => $options
				)
			);
			
			if ($cg=='cg58') {
				echo $default->subform(
					array(
						'Dsp.libformenv'
					),
					array(
						'options' => $options
					)
				);
				echo $dsphm->fieldset( 'Detailfreinform', 'freinform', null, $dsp_id, null, $options['Detailfreinform']['freinform'] );
			}
			/*echo $xform->enum( 'Dsp.hispro', array( 'options' => $options['hispro'] ) );
			echo $xform->input( 'Dsp.libderact', array( 'domain' => 'dsp' ) );
			echo $xform->input( 'Dsp.libsecactderact', array( 'domain' => 'dsp' ) );
			echo $xform->enum( 'Dsp.cessderact', array( 'options' => $options['cessderact'] ) );
			echo $xform->enum( 'Dsp.topdomideract', array( 'options' => $options['topdomideract'] ) );
			echo $xform->input( 'Dsp.libactdomi', array( 'domain' => 'dsp' ) );
			echo $xform->input( 'Dsp.libsecactdomi', array( 'domain' => 'dsp' ) );
			echo $xform->enum( 'Dsp.duractdomi', array( 'options' => $options['duractdomi'] ) );
			echo $xform->enum( 'Dsp.inscdememploi', array( 'options' => $options['inscdememploi'] ) );
			echo $xform->enum( 'Dsp.topisogrorechemploi', array( 'options' => $options['topisogrorechemploi'] ) );
			echo $xform->enum( 'Dsp.accoemploi', array( 'options' => $options['accoemploi'] ) );
			echo $xform->input( 'Dsp.libcooraccoemploi', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
			echo $xform->enum( 'Dsp.topprojpro', array( 'options' => $options['topprojpro'] ) );
			echo $xform->input( 'Dsp.libemploirech', array( 'domain' => 'dsp' ) );
			echo $xform->input( 'Dsp.libsecactrech', array( 'domain' => 'dsp' ) );
			echo $xform->enum( 'Dsp.topcreareprientre', array( 'options' => $options['topcreareprientre'] ) );
			echo $xform->enum( 'Dsp.concoformqualiemploi', array( 'options' => $options['concoformqualiemploi'] ) );*/
		?>
	</fieldset>

	<fieldset>
		<legend>Mobilité</legend>

		<fieldset>
			<legend>Généralités</legend>
			<?php
				echo $default->subform(
					array(
						'Dsp.topmoyloco'
					),
					array(
						'options' => $options
					)
				);
				
				if ($cg=='cg58')
					echo $dsphm->fieldset( 'Detailmoytrans', 'moytrans', 'libautrmoytrans', $dsp_id, '2008', $options['Detailmoytrans']['moytrans'] );
				
				echo $default->subform(
					array(
						'Dsp.toppermicondub',
						'Dsp.topautrpermicondu',
						'Dsp.libautrpermicondu'
					),
					array(
						'options' => $options
					)
				);
				/*echo $xform->enum( 'Dsp.topmoyloco', array( 'options' => $options['topmoyloco'] ) );
				echo $xform->enum( 'Dsp.toppermicondub', array( 'options' => $options['toppermicondub'] ) );
				echo $xform->enum( 'Dsp.topautrpermicondu', array( 'options' => $options['topautrpermicondu'] ) );
				echo $xform->input( 'Dsp.libautrpermicondu', array( 'domain' => 'dsp', 'type' => 'textarea' ) );*/
			?>
		</fieldset>

		<?php
			// Mobilite - DetailMobilite (0-n)
			echo $dsphm->fieldset( 'Detailnatmob', 'natmob', null, $dsp_id, null, $options['Detailnatmob']['natmob'] );
		?>
	</fieldset>

	<fieldset>
		<legend>Difficultés logement</legend>
		<?php
			echo $default->subform(
				array(
					'Dsp.natlog'
				),
				array(
					'options' => $options
				)
			);
			
			if ($cg=='cg58')
				echo $dsphm->fieldset( 'Detailconfort', 'confort', null, $dsp_id, null, $options['Detailconfort']['confort'] );
			
			echo $default->subform(
				array(
					'Dsp.demarlog'
				),
				array(
					'options' => $options
				)
			);
			/*echo $xform->enum( 'Dsp.natlog', array( 'options' => $options['natlog'] ) );
			echo $xform->enum( 'Dsp.demarlog', array( 'options' => $options['demarlog'] ) );*/
		?>

		<?php
			// DifficulteLogement - DetailDifficulteLogement
			echo $dsphm->fieldset( 'Detaildiflog', 'diflog', 'libautrdiflog', $dsp_id, '1009', $options['Detaildiflog']['diflog'] );
		?>
	</fieldset>

	<?php
		echo $xform->submit( 'Form::Save' );
		echo $xform->end();
	?>
</div>
<div class="clearer"><hr /></div>

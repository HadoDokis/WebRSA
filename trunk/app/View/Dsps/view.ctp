<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s' ),
            Set::extract( $personne, 'Personne.nom_complet' )
	);
?>

<div id="dsps">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );

		echo $this->Form->create( 'Dsp', array( 'type' => 'post', 'id' => 'dspform' ) );

		function result( $data, $path, $type, $options = array() ) {
			$result = Set::classicExtract( $data, $path );
			if( $type == 'enum' ) {
				if( !empty( $options['Dsp'][$result] ) ) {
					$result = $options['Dsp'][$result];
				}
			}
			return $result;
		}
// debug( $dsp);
		if( empty( $dsp['Dsp']['id'] ) ) {
			echo '<p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>';

			if( $this->Permissions->checkDossier( 'dsps', 'add', $dossierMenu ) ) {
				echo '<ul class="actionMenu">
						<li>'.$this->Xhtml->addLink(
							'Ajouter une DSP',
							array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
						).' </li></ul>';
			}
		}
		else {
			if( $this->Permissions->checkDossier( 'dsps', 'edit', $dossierMenu ) && ( (isset($rev)) && (!$rev) /*|| ( $this->action == 'view_revs' )*/ ) ) {
				echo '<ul class="actionMenu">
						<li>'.$this->Xhtml->editLink(
							'Modifier cette DSP',
							array( 'controller' => 'dsps', 'action' => 'edit', Set::classicExtract( $dsp, 'Personne.id' ) )
						).' </li></ul>';
			}

			if( $this->Permissions->checkDossier( 'dsps', 'revertTo', $dossierMenu ) && ( $this->action == 'view_revs' ) ) {
				echo '<ul class="actionMenu">
						<li>'.$this->Xhtml->revertToLink(
							'Revenir à cette version',
							array( 'controller' => 'dsps', 'action' => 'revertTo', Set::classicExtract( $dsp, 'Dsp.id' ) )
						).' </li></ul>';
			}

			echo $this->Form->input(
				'Dsp.hideempty',
				array(
					'type' => 'checkbox',
					'label' => 'Cacher les questions sans réponse',
					'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
						$$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
					} else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
				)
			);

			echo $this->Xhtml->tag( 'h2', 'Généralités' );
			echo $this->Default->view(
				$dsp,
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

			echo $this->Xhtml->tag( 'h2', 'Situation sociale' );
			echo $this->Xhtml->tag( 'h3', 'Généralités' );
			echo $this->Default->view(
				$dsp,
				array(
					'Dsp.accosocfam',
					'Dsp.libcooraccosocfam',
					'Dsp.accosocindi',
					'Dsp.libcooraccosocindi',
					'Dsp.soutdemarsoc'
				),
				array(
					'options' => $options
				)
			);

			// SituationSociale - DetailDifficulteSituationSociale (0-n)
			echo $this->Dsphm->details( $dsp, 'Detaildifsoc', 'difsoc', 'libautrdifsoc', $options['Detaildifsoc']['difsoc'] );

			// SituationSociale - DetailDifficulteSituationSocialeProfessionnel (0-n)
			if ($cg=='cg58') {
				echo $this->Dsphm->details( $dsp, 'Detaildifsocpro', 'difsocpro', 'libautrdifsocpro', $options['Detaildifsocpro']['difsocpro'] );
				echo $this->Default->view(
					$dsp,
					array(
						'Dsp.suivimedical'
					),
					array(
						'options' => $options
					)
				);
			}

			// SituationSociale - DetailAccompagnementSocialFamilial (0-n)
			echo $this->Dsphm->details( $dsp, 'Detailaccosocfam', 'nataccosocfam', 'libautraccosocfam', $options['Detailaccosocfam']['nataccosocfam'] );

			// SituationSociale - DetailAccompagnementSocialIndividuel (0-n)
			echo $this->Dsphm->details( $dsp, 'Detailaccosocindi', 'nataccosocindi', 'libautraccosocindi', $options['Detailaccosocindi']['nataccosocindi'] );

			// SituationSociale - DetailDifficulteDisponibilite (0-n)
			echo $this->Dsphm->details( $dsp, 'Detaildifdisp', 'difdisp', null, $options['Detaildifdisp']['difdisp'] );

			// Niveau d'étude
			echo $this->Xhtml->tag( 'h2', 'Niveau d\'étude' );
			echo $this->Default->view(
				$dsp,
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

			// Disponibilités emploi
			echo $this->Xhtml->tag( 'h2', 'Disponibilités emploi' );
			echo $this->Default->view(
				$dsp,
				array(
					'Dsp.topengdemarechemploi'
				),
				array(
					'options' => $options
				)
			);

			// Situation professionnelle
			echo $this->Xhtml->tag( 'h2', 'Situation professionnelle' );
			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				echo $this->Default->view(
					$dsp,
					array(
						'Dsp.hispro',
						'Libsecactderact66Secteur.intitule' => array( 'type' => 'text' ),
						'Dsp.libsecactderact' => array( 'label' => 'Complément d\'information' ),
						'Libderact66Metier.intitule' => array('type' => ('text')),
						'Dsp.libderact' => array( 'label' => 'Complément d\'information' ),
						'Dsp.cessderact',
						'Dsp.topdomideract',
						'Libsecactdomi66Secteur.intitule' => array( 'type' => 'text' ),
						'Dsp.libsecactdomi' => array( 'label' => 'Complément d\'information' ),
						'Libactdomi66Metier.intitule' => array( 'type' => 'text' ),
						'Dsp.libactdomi' => array( 'label' => 'Complément d\'information' ),
						'Dsp.duractdomi',
						'Dsp.inscdememploi',
						'Dsp.topisogrorechemploi',
						'Dsp.accoemploi',
						'Dsp.libcooraccoemploi',
						'Dsp.topprojpro'
					),
					array(
						'options' => $options,
						'domain' => 'dsp'
					)
				);
			}
			else {
				echo $this->Default->view(
					$dsp,
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
						'Dsp.libcooraccoemploi',
						'Dsp.topprojpro'
					),
					array(
						'options' => $options
					)
				);
			}

			if ($cg=='cg58') {
				echo $this->Dsphm->details( $dsp, 'Detailprojpro', 'projpro', 'libautrprojpro', $options['Detailprojpro']['projpro'] );
			}

			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				echo $this->Default->view(
					$dsp,
					array(
						'Libsecactrech66Secteur.intitule' => array( 'type' => 'text' ),
						'Dsp.libsecactrech' => array( 'label' => 'Complément d\'information' ),
						'Libemploirech66Metier.intitule' => array( 'type' => 'text' ),
						'Dsp.libemploirech' => array( 'label' => 'Complément d\'information' ),
						'Dsp.topcreareprientre',
						'Dsp.concoformqualiemploi'
					),
					array(
						'options' => $options,
						'domain' => 'dsp'
					)
				);
			}
			else {
				echo $this->Default->view(
					$dsp,
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
			}

			if ($cg=='cg58') {
				echo $this->Default->view(
					$dsp,
					array(
						'Dsp.libformenv'
					),
					array(
						'options' => $options
					)
				);
				echo $this->Dsphm->details( $dsp, 'Detailfreinform', 'freinform', null, $options['Detailfreinform']['freinform'] );
			}

			// Mobilité
			echo $this->Xhtml->tag( 'h2', 'Mobilité' );
			echo $this->Default->view(
				$dsp,
				array(
					'Dsp.topmoyloco'
				),
				array(
					'options' => $options
				)
			);

			if ($cg=='cg58') {
				echo $this->Dsphm->details( $dsp, 'Detailmoytrans', 'moytrans', 'libautrmoytrans', $options['Detailmoytrans']['moytrans'] );

				echo $this->Default->view(
					$dsp,
					array(
						'Dsp.toppermicondub',
						'Dsp.topautrpermicondu',
						'Dsp.libautrpermicondu'
					),
					array(
						'options' => $options
					)
				);
			}

			// Mobilite - DetailMobilite (0-n)
			echo $this->Dsphm->details( $dsp, 'Detailnatmob', 'natmob', null, $options['Detailnatmob']['natmob'] );


			// Difficultés logement
			echo  $this->Xhtml->tag( 'h2', 'Difficultés logement' );
			echo $this->Default->view(
				$dsp,
				array(
					'Dsp.natlog',
					'Dsp.statutoccupation'
				),
				array(
					'options' => $options
				)
			);

			if ($cg=='cg58')
				echo $this->Dsphm->details( $dsp, 'Detailconfort', 'confort', null, $options['Detailconfort']['confort'] );

			echo $this->Default->view(
				$dsp,
				array(
					'Dsp.demarlog'
				),
				array(
					'options' => $options
				)
			);

			// DifficulteLogement - DetailDifficulteLogement

			echo $this->Dsphm->details( $dsp, 'Detaildiflog', 'diflog', 'libautrdiflog', $options['Detaildiflog']['diflog'] );

// 				foreach( $dsp['Detaildiflog'] as $key => $libautr ){
// 					if( $libautr['diflog'] == '1009' ){
// 						echo  $this->Xhtml->tag( 'div', 'Autres situations particulières' );
// 						echo  $libautr['libautrdiflog'];
// 					}
// 				}

		}
	?>
</div>
<?php if( $this->action == 'view_revs' ):?>
	<div class="submit">
		<?php echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?>
	</div>
	<?php echo $this->Form->end();?>
<?php endif;?>
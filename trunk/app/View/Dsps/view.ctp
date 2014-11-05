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
		echo $this->element( 'ancien_dossier' );

		// ---------------------------------------------------------------------
		// TODO: dans le helper
		function romev3_fields( $type, $suffixes ) {
			$libs = array(
				'deract' => array( 'libsecactderact', 'libderact' ),
				'deractdomi' => array( 'libsecactdomi', 'libactdomi' ),
				'actrech' => array( 'libsecactrech', 'libemploirech' )
			);
			$return = array();

			// TODO: CG 66

			// Idée: en 1 seul bloc ?
			if( Configure::read( 'Romev3.enabled' ) ) {
				foreach( $suffixes as $suffix ) {
					$alias = Inflector::classify( "{$type}{$suffix}romev3" );
					$fieldName = "{$alias}.name";
					$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ) );
				}
			}

			foreach( $libs[$type] as $lib ) {
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					// Dernière activité
					if( $lib === 'libsecactderact' ) {
						$fieldName = 'Libsecactderact66Secteur.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
					else if( $lib === 'libderact' ) {
						$fieldName = 'Libderact66Metier.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
					// Dernière activité dominante
					else if( $lib === 'libsecactdomi' ) {
						$fieldName = 'Libsecactdomi66Secteur.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
					else if( $lib === 'libactdomi' ) {
						$fieldName = 'Libactdomi66Metier.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
					// Activité recherchée
					else if( $lib === 'libsecactrech' ) {
						$fieldName = 'Libsecactrech66Secteur.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
					else if( $lib === 'libemploirech' ) {
						$fieldName = 'Libemploirech66Metier.intitule';
						$return[$fieldName] = array( 'label' => __d( 'dsp', $fieldName ), 'type' => 'text' );
					}
				}

				$fieldName = "Dsp.{$lib}";
				$label = ( Configure::read( 'Cg.departement' ) == 66 ? 'Complément d\'information' : __d( 'dsp', $fieldName ) );
				$return[$fieldName] = array( 'label' => $label );
			}

			return $return;
		}

		// ---------------------------------------------------------------------

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
			$fields = array_merge(
				Hash::normalize(
					array(
						'Dsp.hispro',
					)
				),
				// Dernière activité
				romev3_fields( 'deract', $suffixes ),
				Hash::normalize(
					array(
						'Dsp.cessderact',
						'Dsp.topdomideract',
					)
				),
				// Dernière activité dominante
				romev3_fields( 'deractdomi', $suffixes ),
				Hash::normalize(
					array(
						'Dsp.duractdomi',
						'Dsp.inscdememploi',
						'Dsp.topisogrorechemploi',
						'Dsp.accoemploi',
						'Dsp.libcooraccoemploi',
						'Dsp.topprojpro',
					)
				)
			);
			echo $this->Default->view( $dsp, $fields, array( 'options' => $options ) );

			// FIXME: à ajouter pour le 58
			if ($cg=='cg58') {
				echo $this->Dsphm->details( $dsp, 'Detailprojpro', 'projpro', 'libautrprojpro', $options['Detailprojpro']['projpro'] );
			}

			$fields = array_merge(
				// Emploi recherché
				romev3_fields( 'actrech', $suffixes ),
				Hash::normalize(
					array(
						'Dsp.topcreareprientre',
						'Dsp.concoformqualiemploi'
					)
				)
			);
			echo $this->Default->view( $dsp, $fields, array( 'options' => $options ) );

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
		}
	?>
</div>
<?php if( $this->action == 'view_revs' ):?>
	<div class="submit">
		<?php echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?>
	</div>
	<?php echo $this->Form->end();?>
<?php endif;?>
<?php
	// CSS
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s', true ),
		Set::extract( $personne, 'Personne.qual' ).' '.Set::extract( $personne, 'Personne.nom' ).' '.Set::extract( $personne, 'Personne.prenom' )
	);
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<div id="dsps">
		<?php
			echo $xhtml->tag( 'h1', $this->pageTitle );

			echo $form->create( 'Dsp', array( 'type' => 'post', 'id' => 'dspform', 'url' => Router::url( null, true ) ) );

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

				if( $permissions->check( 'dsps', 'add' ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->addLink(
								'Ajouter une DSP',
								array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
							).' </li></ul>';
				}
			}
			else {
				if( $permissions->check( 'dsps', 'edit' ) && ( (isset($rev)) && (!$rev) /*|| ( $this->action == 'view_revs' )*/ ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->editLink(
								'Modifier cette DSP',
								array( 'controller' => 'dsps', 'action' => 'edit', Set::classicExtract( $dsp, 'Personne.id' ) )
							).' </li></ul>';
				}

				if( $permissions->check( 'dsps', 'revertTo' ) && ( $this->action == 'view_revs' ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->revertToLink(
								'Revenir à cette version',
								array( 'controller' => 'dsps', 'action' => 'revertTo', Set::classicExtract( $dsp, 'Dsp.id' ) )
							).' </li></ul>';
				}

				echo $form->input(
					'Dsp.hideempty',
					array(
						'type' => 'checkbox',
						'label' => 'Cacher les questions sans réponse',
						'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
							$$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
						} else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
					)
				);

				echo $xhtml->tag( 'h2', 'Généralités' );
				echo $default->view(
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

				echo $xhtml->tag( 'h2', 'Situation sociale' );
				echo $xhtml->tag( 'h3', 'Généralités' );
				echo $default->view(
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
				echo $dsphm->details( $dsp, 'Detaildifsoc', 'difsoc', 'libautrdifsoc', $options['Detaildifsoc']['difsoc'] );

				// SituationSociale - DetailDifficulteSituationSocialeProfessionnel (0-n)
				if ($cg=='cg58') {
					echo $dsphm->details( $dsp, 'Detaildifsocpro', 'difsocpro', 'libautrdifsocpro', $options['Detaildifsocpro']['difsocpro'] );
					echo $default->view(
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
				echo $dsphm->details( $dsp, 'Detailaccosocfam', 'nataccosocfam', 'libautraccosocfam', $options['Detailaccosocfam']['nataccosocfam'] );

				// SituationSociale - DetailAccompagnementSocialIndividuel (0-n)
				echo $dsphm->details( $dsp, 'Detailaccosocindi', 'nataccosocindi', 'libautraccosocindi', $options['Detailaccosocindi']['nataccosocindi'] );

				// SituationSociale - DetailDifficulteDisponibilite (0-n)
				echo $dsphm->details( $dsp, 'Detaildifdisp', 'difdisp', null, $options['Detaildifdisp']['difdisp'] );

				// Niveau d'étude
				echo $xhtml->tag( 'h2', 'Niveau d\'étude' );
				echo $default->view(
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
				echo $xhtml->tag( 'h2', 'Disponibilités emploi' );
				echo $default->view(
					$dsp,
					array(
						'Dsp.topengdemarechemploi'
					),
					array(
						'options' => $options
					)
				);

				// Situation professionnelle
				echo $xhtml->tag( 'h2', 'Situation professionnelle' );
				if ( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $default->view(
						$dsp,
						array(
							'Dsp.hispro',
							'Libsecactderact66Secteur.intitule',
							'Dsp.libsecactderact' => array( 'label' => 'Complément d\'information' ),
							'Libderact66Metier.intitule',
							'Dsp.libderact' => array( 'label' => 'Complément d\'information' ),
							'Dsp.cessderact',
							'Dsp.topdomideract',
							'Libsecactdomi66Secteur.intitule',
							'Dsp.libsecactdomi' => array( 'label' => 'Complément d\'information' ),
							'Libactdomi66Metier.intitule',
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
					echo $default->view(
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
					echo $dsphm->details( $dsp, 'Detailprojpro', 'projpro', 'libautrprojpro', $options['Detailprojpro']['projpro'] );
				}

				if ( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $default->view(
						$dsp,
						array(
							'Libsecactrech66Secteur.intitule',
							'Dsp.libsecactrech' => array( 'label' => 'Complément d\'information' ),
							'Libemploirech66Metier.intitule',
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
					echo $default->view(
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
					echo $default->view(
						$dsp,
						array(
							'Dsp.libformenv'
						),
						array(
							'options' => $options
						)
					);
					echo $dsphm->details( $dsp, 'Detailfreinform', 'freinform', null, $options['Detailfreinform']['freinform'] );
				}

				// Mobilité
				echo $xhtml->tag( 'h2', 'Mobilité' );
				echo $default->view(
					$dsp,
					array(
						'Dsp.topmoyloco'
					),
					array(
						'options' => $options
					)
				);

				if ($cg=='cg58') {
					echo $dsphm->details( $dsp, 'Detailmoytrans', 'moytrans', 'libautrmoytrans', $options['Detailmoytrans']['moytrans'] );

					echo $default->view(
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
				echo $dsphm->details( $dsp, 'Detailnatmob', 'natmob', null, $options['Detailnatmob']['natmob'] );


				// Difficultés logement
				echo  $xhtml->tag( 'h2', 'Difficultés logement' );
				echo $default->view(
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
					echo $dsphm->details( $dsp, 'Detailconfort', 'confort', null, $options['Detailconfort']['confort'] );

				echo $default->view(
					$dsp,
					array(
						'Dsp.demarlog'
					),
					array(
						'options' => $options
					)
				);

				// DifficulteLogement - DetailDifficulteLogement

				echo $dsphm->details( $dsp, 'Detaildiflog', 'diflog', 'libautrdiflog', $options['Detaildiflog']['diflog'] );

// 				foreach( $dsp['Detaildiflog'] as $key => $libautr ){
// 					if( $libautr['diflog'] == '1009' ){
// 						echo  $xhtml->tag( 'div', 'Autres situations particulières' );
// 						echo  $libautr['libautrdiflog'];
// 					}
// 				}

			}
		?>
	</div>
</div>
<?php if( $this->action == 'view_revs' ):?>
	<div class="submit">
			<?php
				echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
			?>
		</div>
		<?php echo $form->end();?>
<?php endif;?>
<div class="clearer"><hr /></div>
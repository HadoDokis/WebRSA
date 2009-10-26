<?php
	// CSS
	echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s', true ),
		Set::extract( $dsp, 'Personne.qual' ).' '.Set::extract( $dsp, 'Personne.nom' ).' '.Set::extract( $dsp, 'Personne.prenom' )
	);

	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $dsp, 'Personne.id' ) ) );
?>

<div class="with_treemenu">
	<?php
		echo $html->tag( 'h1', $this->pageTitle );

		function result( $data, $path, $type, $options = array() ) {
			$result = Set::classicExtract( $data, $path );
			if( $type == 'enum' ) {
				if( !empty( $options[$result] ) ) {
					$result = $options[$result];
				}
			}

			return $result;
		}

		if( empty( $dsp['Dsp'] ) ) {
			echo '<p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>';

			if( $permissions->check( 'dsps', 'add' ) ) {
				echo '<ul class="actionMenu">
						<li>'.$html->addLink(
							'Ajouter une DSP',
							array( 'controller' => 'dsps', 'action' => 'add', Set::classicExtract( $dsp, 'Personne.id' ) )
						).' </li></ul>';
			}
		}
		else {
			if( $permissions->check( 'dsps', 'edit' ) ) {
				echo '<ul class="actionMenu">
						<li>'.$html->editLink(
							'Modifier une DSP',
							array( 'controller' => 'dsps', 'action' => 'edit', Set::classicExtract( $dsp, 'Dsp.id' ) )
						).' </li></ul>';
			}

			// Généralités
			$generalites = array(
				array(
					__d( 'dsp', 'Dsp.sitpersdemrsa', true ),
					result( $dsp, 'Dsp.sitpersdemrsa', 'enum', $options['sitpersdemrsa'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topisogroouenf', true ),
					result( $dsp, 'Dsp.topisogroouenf', 'enum', $options['topisogroouenf'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topdrorsarmiant', true ),
					result( $dsp, 'Dsp.topdrorsarmiant', 'enum', $options['topdrorsarmiant'] ),
				),
				array(
					__d( 'dsp', 'Dsp.drorsarmianta2', true ),
					result( $dsp, 'Dsp.drorsarmianta2', 'enum', $options['drorsarmianta2'] ),
				)
			);
			$generalites = $xhtml->details( $generalites, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $generalites ) ) {
				echo $html->tag( 'h2', 'Généralités' ).$generalites;
			}


			// Situation sociale
			// Situation sociale: généralités
			$rows = array(
				array(
					__d( 'dsp', 'Dsp.accosocfam', true ),
					result( $dsp, 'Dsp.accosocfam', 'enum', $options['accosocfam'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libcooraccosocfam', true ),
					result( $dsp, 'Dsp.libcooraccosocfam', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.accosocindi', true ),
					result( $dsp, 'Dsp.accosocindi', 'enum', $options['accosocindi'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libcooraccosocindi', true ),
					result( $dsp, 'Dsp.libcooraccosocindi', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.soutdemarsoc', true ),
					result( $dsp, 'Dsp.soutdemarsoc', 'enum', $options['soutdemarsoc'] ),
				)
			);
			$generalites = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $generalites ) ) {
				$generalites = $html->tag( 'h3', 'Généralités' ).$generalites;
			}

			// Niveau d'étude
			$rows = array(
				array(
					__d( 'dsp', 'Dsp.nivetu', true ),
					result( $dsp, 'Dsp.nivetu', 'enum', $options['nivetu'] ),
				),
				array(
					__d( 'dsp', 'Dsp.nivdipmaxobt', true ),
					result( $dsp, 'Dsp.nivdipmaxobt', 'enum', $options['nivdipmaxobt'] ),
				),
				array(
					__d( 'dsp', 'Dsp.annobtnivdipmax', true ),
					result( $dsp, 'Dsp.annobtnivdipmax', 'text' ),
				),
				array(
					__d( 'dsp', 'Dsp.topqualipro', true ),
					result( $dsp, 'Dsp.topqualipro', 'enum', $options['topqualipro'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libautrqualipro', true ),
					result( $dsp, 'Dsp.libautrqualipro', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.topcompeextrapro', true ),
					result( $dsp, 'Dsp.topcompeextrapro', 'enum', $options['topcompeextrapro'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libcompeextrapro', true ),
					result( $dsp, 'Dsp.libcompeextrapro', 'textarea' ),
				),
			);
			$nivetus = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $nivetus ) ) {
				$nivetus = $html->tag( 'h2', 'Niveau d\'étude' ).$nivetus;
			}

			// Disponibilités emploi
			$disponibilitésEmploi = array(
				array(
					__d( 'dsp', 'Dsp.topengdemarechemploi', true ),
					result( $dsp, 'Dsp.topengdemarechemploi', 'enum', $options['topengdemarechemploi'] ),
				)
			);
			$disponibilitésEmploi = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $disponibilitésEmploi ) ) {
				$disponibilitésEmploi = $html->tag( 'h2', 'Disponibilités emploi' ).$disponibilitésEmploi;
			}

			// Situation professionnelle
			$rows = array(
				array(
					__d( 'dsp', 'Dsp.hispro', true ),
					result( $dsp, 'Dsp.hispro', 'enum', $options['hispro'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libderact', true ),
					result( $dsp, 'Dsp.libderact', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.libsecactderact', true ),
					result( $dsp, 'Dsp.libsecactderact', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.cessderact', true ),
					result( $dsp, 'Dsp.cessderact', 'enum', $options['cessderact'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topdomideract', true ),
					result( $dsp, 'Dsp.topdomideract', 'enum', $options['topdomideract'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libactdomi', true ),
					result( $dsp, 'Dsp.libactdomi', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.libsecactdomi', true ),
					result( $dsp, 'Dsp.libsecactdomi', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.duractdomi', true ),
					result( $dsp, 'Dsp.duractdomi', 'enum', $options['duractdomi'] ),
				),
				array(
					__d( 'dsp', 'Dsp.inscdememploi', true ),
					result( $dsp, 'Dsp.inscdememploi', 'enum', $options['inscdememploi'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topisogrorechemploi', true ),
					result( $dsp, 'Dsp.topisogrorechemploi', 'enum', $options['topisogrorechemploi'] ),
				),
				array(
					__d( 'dsp', 'Dsp.accoemploi', true ),
					result( $dsp, 'Dsp.accoemploi', 'enum', $options['accoemploi'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libcooraccoemploi', true ),
					result( $dsp, 'Dsp.libcooraccoemploi', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.topprojpro', true ),
					result( $dsp, 'Dsp.topprojpro', 'enum', $options['topprojpro'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libemploirech', true ),
					result( $dsp, 'Dsp.libemploirech', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.libsecactrech', true ),
					result( $dsp, 'Dsp.libsecactrech', 'textarea' ),
				),
				array(
					__d( 'dsp', 'Dsp.topcreareprientre', true ),
					result( $dsp, 'Dsp.topcreareprientre', 'enum', $options['topcreareprientre'] ),
				),
				array(
					__d( 'dsp', 'Dsp.concoformqualiemploi', true ),
					result( $dsp, 'Dsp.concoformqualiemploi', 'enum', $options['concoformqualiemploi'] ),
				),
			);
			$situationProfessionnelle = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $situationProfessionnelle ) ) {
				$situationProfessionnelle = $html->tag( 'h2', 'Situation professionnelle' ).$situationProfessionnelle;
			}

			// Mobilité
			$rows = array(
				array(
					__d( 'dsp', 'Dsp.topmoyloco', true ),
					result( $dsp, 'Dsp.topmoyloco', 'enum', $options['topmoyloco'] ),
				),
				array(
					__d( 'dsp', 'Dsp.toppermicondub', true ),
					result( $dsp, 'Dsp.toppermicondub', 'enum', $options['toppermicondub'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topautrpermicondu', true ),
					result( $dsp, 'Dsp.topautrpermicondu', 'enum', $options['topautrpermicondu'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libautrpermicondu', true ),
					result( $dsp, 'Dsp.libautrpermicondu', 'textarea' ),
				),
			);
			$mobilite = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $mobilite ) ) {
				$mobilite = $html->tag( 'h2', 'Mobilité' ).$mobilite;
			}

			// Difficultés logement
			$rows = array(
				array(
					__d( 'dsp', 'Dsp.natlog', true ),
					result( $dsp, 'Dsp.natlog', 'enum', $options['natlog'] ),
				),
				array(
					__d( 'dsp', 'Dsp.demarlog', true ),
					result( $dsp, 'Dsp.demarlog', 'enum', $options['demarlog'] ),
				),
				array(
					__d( 'dsp', 'Dsp.topautrpermicondu', true ),
					result( $dsp, 'Dsp.topautrpermicondu', 'enum', $options['topautrpermicondu'] ),
				),
				array(
					__d( 'dsp', 'Dsp.libautrpermicondu', true ),
					result( $dsp, 'Dsp.libautrpermicondu', 'textarea' ),
				),
			);
			$difficultesLogement = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
			if( !empty( $difficultesLogement ) ) {
				$difficultesLogement = $html->tag( 'h2', 'Difficultés logement' ).$difficultesLogement;
			}

			$situationSolciale = implode( '', array( $generalites, $nivetus, $disponibilitésEmploi, $situationProfessionnelle, $mobilite, $difficultesLogement ) );
			if( !empty( $situationSolciale ) ) {
				echo $html->tag( 'h2', 'Situation sociale' ).$situationSolciale;
			}
		}
	?>
</div>
<div class="clearer"><hr /></div>
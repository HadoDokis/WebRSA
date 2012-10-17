<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	// Titre
	$this->pageTitle = sprintf(
		__( 'Les différences des DSPs de %s' ),
		Set::extract( $personne, 'Personne.nom_complet' )
	);

	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );
?>

<div class="with_treemenu">
	<div class="tab_histo_dsp">
		<?php

			function affiche( $titre, $diff, $act, $old, $values, $options ) {
// if( $values == array( 'DetaildifsocRev.difsoc' ) ) {
// }
				$lignes="";
				$valid = false;
				foreach ($values as $value) {
					$expl = explode('.', $value);
					$modelRevName = $expl[0];
					$modelRevField = $expl[1];
					$existe = false;
					$tableau = false;
					if (isset($diff[$modelRevName])) {
						if (array_key_exists($modelRevField, $diff[$modelRevName])) $existe = true;
						foreach($diff[$modelRevName] as $key => $value1) {
							if (is_int($key)) {
								$existe = true;
								$tableau = true;
							}
						}
					}
					if ($existe) {
						$model_orig = preg_replace( '/Rev$/', '', $modelRevName );
						$path = $model_orig.'.'.$modelRevField;
						if ($tableau) {
							$difference = $diff[$modelRevName];
							$keys = array_keys( $difference[0] );//FIXME: index
							foreach( $keys as $key ) {
								$actValues = Set::extract( "/{$modelRevName}/{$key}", $act );
								$oldValues = Set::extract( "/{$modelRevName}/{$key}", $old );

								$enPlus = Set::filter( array_diff( $actValues, $oldValues ) );
								$enMoins = Set::filter( array_diff( $oldValues, $actValues ) );

								if( !empty( $enPlus ) || !empty( $enMoins ) ) {
									$lignes.="<tr><td style='text-align:center' colspan='2'>".__d( 'dsp', $path )."</td></tr>";

									$row = '';
									// Première cellule, le passé
									if( empty( $oldValues ) ) {
										$row .= '<td><i>champ non renseigné</i></td>';
									}
									else {
										$row .= '<td><ul>';
										foreach( $oldValues as $oldValue ) {
											$class = '';
											if( !empty( $enMoins ) ){
												if( in_array( $oldValue, $enMoins ) ) {
													$class = 'class="enmoins"';
												}
											}
											$row .= '<li '.$class.'>'.Set::enum( $oldValue, $options[$model_orig][$modelRevField] ).'</li>';
										}
										$row .= '</ul></td>';
									}

									// Première cellule, le présent
									if( empty( $actValues ) ) {
										$row .= '<td><i>champ non renseigné</i></td>';
									}
									else {
										$row .= '<td><ul>';
										foreach( $actValues as $actValue ) {
											$class = '';
											if( !empty( $enPlus ) ){
												if( in_array( $actValue, $enPlus ) ) {
													$class = 'class="enplus"';
												}
											}
											$row .= '<li '.$class.'>'.Set::enum( $actValue, $options[$model_orig][$modelRevField] ).'</li>';
										}
										$row .= '</ul></td>';
									}
									$lignes .= "<tr>{$row}</tr>";
								}
							}
						}
						else {
							if (Set::check($options, $model_orig.'.'.$modelRevField)) {
								$actValue = Set::enum(Set::classicExtract($act, $value), $options[$model_orig][$modelRevField]);
								$oldValue = Set::enum(Set::classicExtract($old, $value), $options[$model_orig][$modelRevField]);
							}
							else {
								$actValue = Set::classicExtract($act, $value);
								$oldValue = Set::classicExtract($old, $value);
							}
							if (empty($actValue)) $actValue = "<i>champ non renseigné</i>";
							if (empty($oldValue)) $oldValue = "<i>champ non renseigné</i>";
							$lignes.="<tr><td style='text-align:center' colspan='2'>".__d( 'dsp', $path )."</td></tr>";
							$lignes.="<tr><td>".$oldValue."</td><td>".$actValue."</td></tr>";
						}
						$valid = true;
					}
				}
				if ($valid) {
					return "<tr><th colspan='2'>".$titre."</th></tr>".$lignes;
				}
				else return "";
			}

			echo $this->Xhtml->tag( 'h1', $this->pageTitle );

			echo "<table class=\"diff_dsps\">";
			echo "<tr><th><h2 style='text-align:center'>DSP précédente</h2></th><th><h2 style='text-align:center'>DSP choisie</h2></th></tr>";

			echo affiche('<h2>Généralités</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.sitpersdemrsa', 'DspRev.topisogroouenf', 'DspRev.topdrorsarmiant', 'DspRev.drorsarmianta2', 'DspRev.topcouvsoc'), $options);

			echo affiche('<h3>Généralités</h3>', $diff, $dsprevact, $dsprevold, array( 'DspRev.accosocfam', 'DspRev.libcooraccosocfam', 'DspRev.accosocindi', 'DspRev.libcooraccosocindi', 'DspRev.soutdemarsoc'), $options);

			echo affiche('<h3>Difficultés sociales</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifsocRev.difsoc'), $options);

			if ($cg=='cg58') {
				echo affiche('<h3>Difficultés sociales décelées par le professionel</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifsocproRev.difsocpro'), $options);
			}

			echo affiche('<h3>Difficultés accompagnement social familial</h3>', $diff, $dsprevact, $dsprevold, array( 'DetailaccosocfamRev.nataccosocfam'), $options);

			echo affiche('<h3>Difficultés accompagnement social individuel</h3>', $diff, $dsprevact, $dsprevold, array( 'DetailaccosocindiRev.nataccosocindi'), $options);

			echo affiche('<h3>Difficultés disponibilités</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifdispRev.difdisp'), $options);

			echo affiche('<h2>Niveau d\'étude</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.nivetu', 'DspRev.nivdipmaxobt', 'DspRev.annobtnivdipmax', 'DspRev.topqualipro', 'DspRev.libautrqualipro', 'DspRev.topcompeextrapro', 'DspRev.libcompeextrapro'), $options);

			echo affiche('<h2>Disponibilités emploi</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.topengdemarechemploi'), $options);

			if ($cg=='cg58') {
				$liste = array( 'DspRev.hispro', 'DspRev.libsecactderact', 'DspRev.libderact', 'DspRev.cessderact', 'DspRev.topdomideract', 'DspRev.libactdomi', 'DspRev.libsecactdomi', 'DspRev.duractdomi', 'DspRev.inscdememploi', 'DspRev.topisogrorechemploi', 'DspRev.accoemploi', 'DspRev.libcooraccoemploi', 'DspRev.topprojpro', 'DetailprojproRev.projpro', 'DspRev.libemploirech', 'DspRev.libsecactrech', 'DspRev.topcreareprientre', 'DspRev.concoformqualiemploi', 'DspRev.libformenv', 'DetailfreinformRev.freinform');
			}
			elseif ($cg=='cg66') {
				$liste = array( 'DspRev.hispro', 'DspRev.libsecactderact', 'Libsecactderact66Secteur.intitule', 'DspRev.libderact', 'Libderact66Metier.intitule', 'DspRev.cessderact', 'DspRev.topdomideract', 'Libsecactdomi66Secteur.intitule', 'DspRev.libsecactdomi', 'Libactdomi66Metier.intitule', 'DspRev.libactdomi', 'DspRev.duractdomi', 'DspRev.inscdememploi', 'DspRev.topisogrorechemploi', 'DspRev.accoemploi', 'DspRev.libcooraccoemploi', 'DspRev.topprojpro', 'DspRev.libsecactrech', 'Libsecactrech66Secteur.intitule', 'DspRev.libemploirech', 'Libemploirech66Metier.intitule', 'DspRev.topcreareprientre', 'DspRev.concoformqualiemploi');
			}
			else {
				$liste = array( 'DspRev.hispro', 'DspRev.libsecactderact', 'DspRev.libderact', 'DspRev.cessderact', 'DspRev.topdomideract', 'DspRev.libactdomi', 'DspRev.libsecactdomi', 'DspRev.duractdomi', 'DspRev.inscdememploi', 'DspRev.topisogrorechemploi', 'DspRev.accoemploi', 'DspRev.libcooraccoemploi', 'DspRev.topprojpro', 'DspRev.libemploirech', 'DspRev.libsecactrech', 'DspRev.topcreareprientre', 'DspRev.concoformqualiemploi');
			}

			echo affiche('<h2>Situation professionnelle</h2>', $diff, $dsprevact, $dsprevold, $liste, $options);

			if ($cg=='cg58')
				$liste = array( 'DspRev.topmoyloco', 'DetailmoytransRev.moytrans', 'DspRev.toppermicondub', 'DspRev.topautrpermicondu', 'DspRev.libautrpermicondu');
			else
				$liste = array( 'DspRev.topmoyloco', 'DspRev.toppermicondub', 'DspRev.topautrpermicondu', 'DspRev.libautrpermicondu');

			echo affiche('<h2>Mobilité</h2>', $diff, $dsprevact, $dsprevold, $liste, $options);

			echo affiche('<h3>Code mobilité</h3>', $diff, $dsprevact, $dsprevold, array( 'DetailnatmobRev.natmob'), $options);

			if ($cg=='cg58')
				$liste = array( 'DspRev.natlog', 'DetailconfortRev.confort', 'DspRev.demarlog');
			else
				$liste = array( 'DspRev.natlog', 'DspRev.demarlog');

			echo affiche('<h2>Difficultés logement</h2>', $diff, $dsprevact, $dsprevold, $liste, $options);

			echo affiche('<h3>Détails difficultés logement</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildiflogRev.diflog'), $options);

			echo "</table>";
		?>
	</div>
</div>
<div class="clearer"><hr /></div>
<?php
	// CSS
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Les différences des DSPs de %s', true ),
		Set::extract( $personne, 'Personne.qual' ).' '.Set::extract( $personne, 'Personne.nom' ).' '.Set::extract( $personne, 'Personne.prenom' )
	);

	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );
?>

<div class="with_treemenu">
	<div class="tab_histo_dsp">
		<?php
//debug($diff);
//debug($dsprevact);
//debug($dsprevold);

/*debug( Set::enum( 1, $options['Dsp']['topisogroouenf'] ) );
debug( __d( 'dsp', 'Dsp.topisogroouenf', true ) );
debug( $options );*/

			function affiche( $titre, $diff, $act, $old, $values, $options ) {
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
						$model_orig = substr($modelRevName,0,-3);
						$path = $model_orig.'.'.$modelRevField;
						if ($tableau) {
							$lignes.="<tr><td style='text-align:center' colspan='2'>".__d( 'dsp', $path, true )."</td></tr>";
							foreach($diff[$modelRevName] as $key=>$value2) {
								if (isset($act[$modelRevName][$key][$modelRevField]))
									$actValue = Set::enum($act[$modelRevName][$key][$modelRevField], $options[$model_orig][$modelRevField]);
								else
									$actValue = "<i>champ non renseigné</i>";
									
								if (isset($old[$modelRevName][$key][$modelRevField]))
									$oldValue = Set::enum($old[$modelRevName][$key][$modelRevField], $options[$model_orig][$modelRevField]);
								else
									$oldValue = "<i>champ non renseigné</i>";
									
								$lignes.="<tr><td>".$oldValue."</td><td>".$actValue."</td></tr>";
								
								if (isset($act[$modelRevName][$key]['libautr'.$modelRevField])) $actlib="=>".$act[$modelRevName][$key]['libautr'.$modelRevField];
								else $actlib="<i>=> champ non renseigné</i>";
								if (isset($old[$modelRevName][$key]['libautr'.$modelRevField])) $oldlib="=>".$old[$modelRevName][$key]['libautr'.$modelRevField];
								else $oldlib="<i>=> champ non renseigné</i>";
								if ($actlib != $oldlib) {
									$lignes.="<tr><td>".$oldlib."</td><td>".$actlib."</td></tr>";
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
							$lignes.="<tr><td style='text-align:center' colspan='2'>".__d( 'dsp', $path, true )."</td></tr>";
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
		
			echo $xhtml->tag( 'h1', $this->pageTitle );
			
			echo "<table>";
			echo "<tr><th><h2 style='text-align:center'>DSP précédente</h2></th><th><h2 style='text-align:center'>DSP choisie</h2></th></tr>";
			
			echo affiche('<h2>Généralités</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.sitpersdemrsa', 'DspRev.topisogroouenf', 'DspRev.topdrorsarmiant', 'DspRev.drorsarmianta2', 'DspRev.topcouvsoc'), $options);
			
			echo affiche('<h3>Généralités</h3>', $diff, $dsprevact, $dsprevold, array( 'DspRev.accosocfam', 'DspRev.libcooraccosocfam', 'DspRev.accosocindi', 'DspRev.libcooraccosocindi', 'DspRev.soutdemarsoc'), $options);
			
			echo affiche('<h3>Difficultés sociales</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifsocRev.difsoc'), $options);
			
			if ($cg=='cg58')
				echo affiche('<h3>Difficultés sociales décelées par le professionel</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifsocproRev.difsocpro'), $options);
			
			echo affiche('<h3>Difficultés accompagnement social familial</h3>', $diff, $dsprevact, $dsprevold, array( 'DetailaccosocfamRev.nataccosocfam'), $options);
			
			echo affiche('<h3>Difficultés accompagnement social individuel</h3>', $diff, $dsprevact, $dsprevold, array( 'DetailaccosocindiRev.nataccosocindi'), $options);
			
			echo affiche('<h3>Difficultés disponibilités</h3>', $diff, $dsprevact, $dsprevold, array( 'DetaildifdispRev.difdisp'), $options);
			
			echo affiche('<h2>Niveau d\'étude</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.nivetu', 'DspRev.nivdipmaxobt', 'DspRev.annobtnivdipmax', 'DspRev.topqualipro', 'DspRev.libautrqualipro', 'DspRev.topcompeextrapro', 'DspRev.libcompeextrapro'), $options);
			
			echo affiche('<h2>Disponibilités emploi</h2>', $diff, $dsprevact, $dsprevold, array( 'DspRev.topengdemarechemploi'), $options);
			
			if ($cg=='cg58')
				$liste = array( 'DspRev.hispro', 'DspRev.libderact', 'DspRev.libsecactderact', 'DspRev.cessderact', 'DspRev.topdomideract', 'DspRev.libactdomi', 'DspRev.libsecactdomi', 'DspRev.duractdomi', 'DspRev.inscdememploi', 'DspRev.topisogrorechemploi', 'DspRev.accoemploi', 'DspRev.libcooraccoemploi', 'DspRev.topprojpro', 'DetailprojproRev.projpro', 'DspRev.libemploirech', 'DspRev.libsecactrech', 'DspRev.topcreareprientre', 'DspRev.concoformqualiemploi', 'DspRev.libformenv', 'DetailfreinformRev.freinform');
			else
				$liste = array( 'DspRev.hispro', 'DspRev.libderact', 'DspRev.libsecactderact', 'DspRev.cessderact', 'DspRev.topdomideract', 'DspRev.libactdomi', 'DspRev.libsecactdomi', 'DspRev.duractdomi', 'DspRev.inscdememploi', 'DspRev.topisogrorechemploi', 'DspRev.accoemploi', 'DspRev.libcooraccoemploi', 'DspRev.topprojpro', 'DspRev.libemploirech', 'DspRev.libsecactrech', 'DspRev.topcreareprientre', 'DspRev.concoformqualiemploi');
			
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

<?php /*debug( $dsp );*/ ?>

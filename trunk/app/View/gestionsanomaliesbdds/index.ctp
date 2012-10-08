<h1><?php echo __d( 'gestionanomaliebdd', 'Gestionsanomaliesbdds::index', true );?></h1>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<noscript>
	<p class="error">Cette fonctionnalité nécessite l'utilisation de javascript, mais javascript n'est pas activé dans votre navigateur.</p>
</noscript>

<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $xform->create( null, array( 'id' => 'Search', 'class' => 'gestionsanomaliesbdd index' ) );
	// Types de problèmes à détecter
	echo '<fieldset id="SearchProblemes"><legend>Détection des problèmes</legend>'.$xform->input(
			'Gestionanomaliebdd.touteerreur', array( 'type' => 'checkbox', 'domain' => 'gestionanomaliebdd' )
	);
	echo '<fieldset id="SearchTypesProblemes"><legend>Types de problèmes à détecter</legend>'.$default2->subform(
		array(
			'Gestionanomaliebdd.enerreur' => array( 'type' => 'select', 'empty' => true, 'domain' => 'gestionanomaliebdd', 'div' => array( 'class' => 'input select enerreur' ) ),
			'Gestionanomaliebdd.sansprestation' => array( 'type' => 'select', 'empty' => true, 'domain' => 'gestionanomaliebdd', 'div' => array( 'class' => 'input select sansprestation' ) ),
			'Gestionanomaliebdd.doublons' => array( 'type' => 'select', 'empty' => true, 'domain' => 'gestionanomaliebdd', 'div' => array( 'class' => 'input select doublons' ) ),
		),
		array(
			'options' => $options,
			'form' => false,
		)
	).'</fieldset>'.$xform->input(
			'Gestionanomaliebdd.methode', array( 'type' => 'select', 'empty' => false, 'domain' => 'gestionanomaliebdd', 'options' => $options['Gestionanomaliebdd']['methode'] )
	).'</fieldset>';
	// Filtre sur le dossier
	echo '<fieldset id="SearchFiltreDossier"><legend>Filtrer sur le dossier</legend>'.$default2->subform(
		array(
			'Dossier.numdemrsa' => array( 'type' => 'text', 'domain' => 'gestionanomaliebdd' ),
			'Dossier.dtdemrsa' => array( 'domain' => 'gestionanomaliebdd' ),
			'Dossier.matricule' => array( 'type' => 'text', 'domain' => 'gestionanomaliebdd' ),
			'Foyer.sitfam' => array( 'domain' => 'gestionanomaliebdd' ),
			'Foyer.ddsitfam' => array( 'domain' => 'gestionanomaliebdd' ),
			'Situationdossierrsa.etatdosrsa' => array( 'multiple' => 'checkbox', 'empty' => false, 'domain' => 'gestionanomaliebdd' ),
		),
		array(
			'options' => $options,
			'form' => false,
		)
	).'</fieldset>';
	// Filtre sur l'adresse actuelle
	echo '<fieldset id="SearchFiltreAdresse"><legend>Filtrer sur l\'adresse actuelle</legend>'.$default2->subform(
		array(
			'Adresse.locaadr' => array( 'domain' => 'gestionanomaliebdd' ),
			'Adresse.numcomptt' => array( 'domain' => 'gestionanomaliebdd' ),//FIXME: ne fonctionne pas au 66
		),
		array(
			'options' => $options,
			'form' => false,
		)
	).'</fieldset>';
	// Filtrer sur une personne du dossier
	echo '<fieldset id="SearchFiltrePersonne"><legend>Filtrer sur une personne du foyer</legend>'.$default2->subform(
		array(
			'Personne.nom' => array( 'domain' => 'gestionanomaliebdd' ),
			'Personne.prenom' => array( 'domain' => 'gestionanomaliebdd' ),
			'Personne.nir' => array( 'domain' => 'gestionanomaliebdd' ),
		),
		array(
			'options' => $options,
			'form' => false,
		)
	).'</fieldset>';
	echo $xform->end( 'Rechercher' );

	if( isset( $results ) ) {
		$filtresErreur = array(
			'touteerreur' => @$this->data['Gestionanomaliebdd']['touteerreur'],
			'enerreur' => @$this->data['Gestionanomaliebdd']['enerreur'],
			'sansprestation' => @$this->data['Gestionanomaliebdd']['sansprestation'],
			'doublons' => @$this->data['Gestionanomaliebdd']['doublons']
		);
		$filtresErreurNull = true;
		foreach( $filtresErreur as $filtreErreur ) {
			if( !is_null( $filtreErreur ) ) {
				$filtresErreurNull = false;
			}
		}
		if( $filtresErreurNull ) {
			echo $html->tag( 'p', 'Vous n\'avez sélectionné aucun filtre concernant les types de problèmes', array( 'class' => 'notice' ) );
		}

		if( empty( $results ) ) {
			echo $html->tag( 'p', 'Aucun résultat ne correspond à vos critères.', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $xpaginator2->paginationBlock( 'Dossier', $this->passedArgs );

			$urlParams = Set::flatten( $this->data, '__' );

			$thead = '<tr>'
					.'<th>'.$xpaginator2->sort( __d( 'dossier', 'Dossier.numdemrsa', true ), 'Dossier.numdemrsa' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'dossier', 'Dossier.dtdemrsa', true ), 'Dossier.dtdemrsa' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'dossier', 'Dossier.matricule', true ), 'Dossier.matricule' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'foyer', 'Foyer.sitfam', true ), 'Foyer.sitfam' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'gestionanomaliebdd', 'Foyer.ddsitfam', true ), 'Foyer.ddsitfam' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'situationdossierrsa', 'Situationdossierrsa.etatdosrsa', true ), 'Situationdossierrsa.etatdosrsa' ).'</th>'
					.'<th>'.$xpaginator2->sort( __d( 'adresse', 'Adresse.locaadr', true ), 'Adresse.locaadr' ).'</th>'
					.'<th class="action noprint" colspan="3">Problèmes détectés</th>'
					.'<th class="action noprint">Verrouillé ?</th>'
					.'<th class="action noprint" colspan="2">Actions</th>'
					.'<th class="innerTableHeader noprint">Informations complémentaires</th>'
				.'</tr>';

			$innerThead = $html->tableHeaders(
				array(
					__d( 'personne', 'Personne.qual', true ),
					__d( 'personne', 'Personne.nom', true ),
					__d( 'personne', 'Personne.prenom', true ),
					__d( 'personne', 'Personne.nomnai', true ),
					__d( 'personne', 'Personne.dtnai', true ),
					__d( 'personne', 'Personne.nir', true ),
					__d( 'prestation', 'Prestation.rolepers', true )
				)
			);

			$tbody = '';
			foreach( $results as $i => $result ) {
				$rowId = "innerTableTrigger{$i}";

				$innerTbody = '';
				foreach( $result['Doublons'] as $doublon ) {
					$innerRow = "<td>".$type2->format( $doublon, 'Personne.qual', array( 'options' => $options ) )."</td>";
					$innerRow .= "<td>{$doublon['Personne']['nom']}</td>";
					$innerRow .= "<td>{$doublon['Personne']['prenom']}</td>";
					$innerRow .= "<td>{$doublon['Personne']['nomnai']}</td>";
					$innerRow .= "<td>".$type2->format( $doublon, 'Personne.dtnai' )."</td>";
					$innerRow .= "<td>{$doublon['Personne']['nir']}</td>";
					$innerRow .= "<td>".$type2->format( $doublon, 'Prestation.rolepers', array( 'options' => $options ) )."</td>";

					$innerTbody .= "<tr>{$innerRow}</tr>";
				}

				if( empty( $innerTbody ) ) {
					$innerTable = "<table id=\"innerTablesearchResults{$i}\" class=\"innerTable\"><tbody><tr><td>Aucun doublon de personnes détecté</td></tr></tbody></table>";
				}
				else {
					$innerTable = "<table id=\"innerTablesearchResults{$i}\" class=\"innerTable\"><thead>{$innerThead}</thead><tbody>{$innerTbody}</tbody></table>";
				}

				$correction = ( !empty( $result['Foyer']['enerreur'] ) || !empty( $result['Foyer']['sansprestation'] ) || !empty( $result['Foyer']['doublonspersonnes'] ) );

				$tbody .= $html->tableCells(
					array(
						h( Set::classicExtract( $result, 'Dossier.numdemrsa' ) ),
						$type2->format( $result, 'Dossier.dtdemrsa' ),
						h( Set::classicExtract( $result, 'Dossier.matricule' ) ),
						h( @$options['Foyer']['sitfam'][Set::classicExtract( $result, 'Foyer.sitfam' )] ),
						$type2->format( $result, 'Foyer.ddsitfam' ),
						h( @$options['Situationdossierrsa']['etatdosrsa'][Set::classicExtract( $result, 'Situationdossierrsa.etatdosrsa' )] ),
						h( Set::classicExtract( $result, 'Adresse.locaadr' ) ),
						array( $gestionanomaliebdd->foyerErreursPrestationsAllocataires( $result, false ), array( 'class' => 'icon' ) ),
						array( $gestionanomaliebdd->foyerPersonnesSansPrestation( $result, false ), array( 'class' => 'icon' ) ),
						array( $gestionanomaliebdd->foyerErreursDoublonsPersonnes( $result, false ), array( 'class' => 'icon' ) ),
						( $result['Dossier']['locked'] ? $xhtml->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null ),
						array( $default2->button( 'view', array( 'controller' => 'personnes', 'action' => 'index', $result['Foyer']['id'] ), array( 'label' => 'Voir', 'title' => sprintf( 'Voir le dossier « %s »', $result['Dossier']['numdemrsa'] ) ) ), array( 'class' => 'noprint' ) ),
						array(
							$default2->button(
								'edit',
								array_merge( array( 'action' => 'foyer', $result['Foyer']['id'] ), $urlParams ),
								array( 'label' => 'Corriger', 'enabled' => $correction, 'title' => sprintf( 'Corriger le dossier « %s »', $result['Dossier']['numdemrsa'] ), 'class' => 'external' )
							), array( 'class' => 'noprint' )
						),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
					),
					array( 'class' => 'odd', 'id' => $rowId ),
					array( 'class' => 'even', 'id' => $rowId )
				);
			}
			echo $pagination.'<table id="searchResults" class="tooltips default2 gestionsanomaliesbdd"><thead>'.$thead.'</thead><tbody>'.$tbody.'</tbody></table>'.$pagination;
		}
	}

	echo $javascript->codeBlock(
		"\$('Search').removeClassName( 'folded' ); \$('Search').removeClassName( 'unfolded' ); \$('Search').addClassName( '".( isset( $results ) ? 'folded' : 'unfolded' )."' );",
		array(
			'allowCache' => false,
			'safe' => true,
			'inline'=>true
		)
	);
?>
<script type="text/javascript">
//<![CDATA[
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'GestionanomaliebddTouteerreur', $( 'SearchTypesProblemes' ), true );
	} );
//]]>
</script>
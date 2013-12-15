<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<h1><?php echo $this->pageTitle = __d( 'defautinsertionep66', 'Defautsinsertionseps66::courriersinformations' );?></h1>

<?php if( is_array( $this->request->data ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->link(
				$this->Xhtml->image(
					'icons/application_form_magnify.png',
					array( 'alt' => '' )
				).' Formulaire',
				'#',
				array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
			).'</li>';
		?>
	</ul>
<?php endif;?>

<?php
	// Formulaire
	echo $this->Xform->create( null, array( 'id' => 'Search' ) );

	$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;

	echo $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', 'Recherche par bénéficiaire' ).
		$this->Default2->subform(
			array(
				'Search.Personne.nom' => array(  'label' => __d( 'personne', 'Personne.nom' ), 'required' => false ),
				'Search.Personne.nomnai' => array(  'label' => __d( 'personne', 'Personne.nomnai' ) ),
				'Search.Personne.prenom' => array(  'label' => __d( 'personne', 'Personne.prenom' ), 'required' => false ),
				'Search.Personne.nir' => array(  'label' => __d( 'personne', 'Personne.nir' ) ),
				'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule' ) ),
				'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa' ), 'required' => false ),
				'Search.Dossier.dernier' => array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier )
			)
		)
	);

	$fields = array(
		'Search.Adresse.locaadr' => array(  'label' => __d( 'adresse', 'Adresse.locaadr' ), 'required' => false ),
		'Search.Adresse.numcomptt' => array( 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true, 'label' => __d( 'adresse', 'Adresse.numcomptt' ), 'required' => false )
	);
	if( Configure::read( 'CG.cantons' ) ) {
		$fields['Search.Canton.canton'] = array( 'type' => 'select', 'options' => $cantons, 'empty' => true, 'label' => 'Canton', 'required' => false );
	}

	echo $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', 'Recherche par Adresse' ).
		$this->Default->subform(
			$fields
		)
	);

	echo $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', 'Recherche par courriers' ).
		$this->Default->subform(
			array(
				'Search.Defautinsertionep66.isimprime' => array( 'type' => 'select', 'options' => $printed, 'label' => 'Imprimé / Non imprimé', 'required' => false )
			)
		)
	);

	echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours, 'Search' );
	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );
	echo $this->Search->observeDisableFormOnSubmit( 'Search' );

	echo $this->Xform->end( __( 'Rechercher' ) );
	// Résultats
	if( isset( $results ) ) {
		if( empty( $results ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucun résultat ne correspond à ces critères.', array( 'class' => 'notice' ) );
		}
		else {
			echo '<ul class="actionMenu">';
				echo '<li>'.$this->Xhtml->link(
					'Imprimer les courriers d\'information',
					array( 'controller' => 'defautsinsertionseps66', 'action' => 'printCourriersInformations' ) + Hash::flatten( $this->request->data, '__' ),
					array( 'class' => 'button print', 'enabled' => $this->Permissions->check( 'defautsinsertionseps66', 'printCourriersInformations' ) ),
					'Etes-vous sûr de vouloir imprimer les courriers d\'information ?'
				).' </li>';
			echo '</ul>';

			echo $this->Xpaginator->paginationBlock( 'Dossierep', $this->passedArgs );

			echo '<table class="tooltips" style="width: 100%;"><thead>';
				echo '<tr>';
					echo '<th>'.$this->Xpaginator->sort( 'NIR', 'Personne.nir' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'N° CAF', 'Dossier.matricule' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'Nom', 'Personne.nom' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'Prénom', 'Personne.prenom' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'Code Postal', 'Adresse.numcomptt' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'Commune', 'Adresse.locaadr' ).'</th>';
					echo '<th>'.$this->Xpaginator->sort( 'Date de création du dossier d\'EP', 'Dossierep.created' ).'</th>';
					echo '<th>Action</th>';
				echo '</tr>';
			echo '</thead><tbody>';
				foreach( $results as $result ) {
					echo '<tr>';
						echo $this->Xhtml->tag(
							'td',
							$result['Personne']['nir']
						);
						echo $this->Xhtml->tag(
							'td',
							$result['Dossier']['matricule']
						);
						echo $this->Xhtml->tag(
							'td',
							$result['Personne']['nom']
						);
						echo $this->Xhtml->tag(
							'td',
							$result['Personne']['prenom']
						);
						echo $this->Xhtml->tag(
							'td',
							$result['Adresse']['numcomptt']
						);
						echo $this->Xhtml->tag(
							'td',
							$result['Adresse']['locaadr']
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Locale->date( 'Datetime::short', $result['Dossierep']['created'] )
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Xhtml->link(
								'Courrier d\'information',
								array(
									'controller' => 'dossierseps',
									'action' => 'courrierInformation',
									$result['Dossierep']['id'],
								),
								array(
									'disabled' => !$this->Permissions->check( 'dossierseps', 'courrierInformation' )
								)
							)
						);
					echo '</tr>';
				}
			echo '</tbody><table>';

			echo $this->Xpaginator->paginationBlock( 'Dossierep', $this->passedArgs );
		}
	}
?>
<script type="text/javascript">
	var form = $$( 'form' );
	form = form[0];
	<?php if( isset( $results ) ):?>$( form ).hide();<?php endif;?>
</script>

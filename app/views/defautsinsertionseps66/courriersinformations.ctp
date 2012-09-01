<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<h1><?php echo $this->pageTitle = __d( 'defautinsertionep66', 'Defautsinsertionseps66::courriersinformations', true );?></h1>

<?php if( is_array( $this->data ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->link(
				$xhtml->image(
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
	echo $xform->create();

	$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par bénéficiaire' ).
		$default->subform(
			array(
				'Search.Personne.nom' => array( 'type' => 'text', 'label' => __d( 'personne', 'Personne.nom', true ), 'required' => false ),
				'Search.Personne.nomnai' => array( 'type' => 'text', 'label' => __d( 'personne', 'Personne.nomnai', true ) ),
				'Search.Personne.prenom' => array( 'type' => 'text', 'label' => __d( 'personne', 'Personne.prenom', true ), 'required' => false ),
				'Search.Personne.nir' => array( 'type' => 'text', 'label' => __d( 'personne', 'Personne.nir', true ) ),
				'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15, 'required' => false ),
				'Search.Dossier.dernier' => array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier )
			)
		)
	);

	$fields = array(
		'Search.Adresse.locaadr' => array( 'type' => 'text', 'label' => __d( 'adresse', 'Adresse.locaadr', true ), 'required' => false ),
		'Search.Adresse.numcomptt' => array( 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true, 'label' => __d( 'adresse', 'Adresse.numcomptt', true ), 'required' => false )
	);
	if( Configure::read( 'CG.cantons' ) ) {
		$fields['Search.Canton.canton'] = array( 'type' => 'select', 'options' => $cantons, 'empty' => true, 'label' => 'Canton', 'required' => false );
	}

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par Adresse' ).
		$default->subform(
			$fields
		)
	);

	echo $xform->end( __( 'Rechercher', true ) );
	// Résultats
	if( isset( $results ) ) {
		if( empty( $results ) ) {
			echo $xhtml->tag( 'p', 'Aucun résultat ne correspond à ces critères.', array( 'class' => 'notice' ) );
		}
		else {
			echo '<ul class="actionMenu">';
				echo '<li>'.$xhtml->link(
					'Imprimer les courriers d\'information',
					array( 'controller' => 'defautsinsertionseps66', 'action' => 'printCourriersInformations' ) + Set::flatten( $this->data, '__' ),
					array( 'class' => 'button print' ),
					'Etes-vous sûr de vouloir imprimer les courriers d\'information ?'
				).' </li>';
			echo '</ul>';

			echo $xpaginator->paginationBlock( 'Defautinsertionep66', $this->passedArgs );

			echo '<table class="tooltips" style="width: 100%;"><thead>';
				echo '<tr>';
					echo '<th>'.$xpaginator->sort( 'NIR', 'Personne.nir' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'N° CAF', 'Dossier.matricule' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'Nom', 'Personne.nom' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'Prénom', 'Personne.prenom' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'Code Postal', 'Adresse.numcomptt' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'Commune', 'Adresse.locaadr' ).'</th>';
					echo '<th>'.$xpaginator->sort( 'Date de création du dossier d\'EP', 'Dossierep.created' ).'</th>';
					echo '<th>Action</th>';
				echo '</tr>';
			echo '</thead><tbody>';
				foreach( $results as $result ) {
					echo '<tr>';
						echo $xhtml->tag(
							'td',
							$result['Personne']['nir']
						);
						echo $xhtml->tag(
							'td',
							$result['Dossier']['matricule']
						);
						echo $xhtml->tag(
							'td',
							$result['Personne']['nom']
						);
						echo $xhtml->tag(
							'td',
							$result['Personne']['prenom']
						);
						echo $xhtml->tag(
							'td',
							$result['Adresse']['numcomptt']
						);
						echo $xhtml->tag(
							'td',
							$result['Adresse']['locaadr']
						);
						echo $xhtml->tag(
							'td',
							$locale->date( 'Datetime::short', $result['Dossierep']['created'] )
						);
						echo $xhtml->tag(
							'td',
							$xhtml->link( 'Courrier d\'information', array( 'controller' => 'dossierseps', 'action' => 'courrierInformation', $result['Dossierep']['id'] ) )
						);
					echo '</tr>';
				}
			echo '</tbody><table>';

			echo $xpaginator->paginationBlock( 'Defautinsertionep66', $this->passedArgs );
		}
	}
?>
<script type="text/javascript">
	var form = $$( 'form' );
	form = form[0];
	<?php if( isset( $results ) ):?>$( form ).hide();<?php endif;?>
</script>

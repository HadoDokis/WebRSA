<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'offreinsertion', "Offresinsertion::{$this->action}", true )
	)
?>
<?php
	// Formulaire de recherche des actions, partenaires et contacts
    echo '<ul class="actionMenu"><li>'.$xhtml->link(
        $xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';

    //Création du formulaire
    echo $xform->create( 'Offreinsertion', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>
	<fieldset>
		<?php
			echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );

			echo $default2->subform(
				array(
					'Search.Actioncandidat.name' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.name', true ), 'type' => 'select', 'options' => $listeActions, 'empty' => true ),
					'Search.Partenaire.id' => array( 'label' => __d( 'partenaire', 'Partenaire.libstruc', true ), 'type' => 'select', 'options' => $listePartenaires, 'empty' => true ),
					'Search.Contactpartenaire.id' => array( 'label' => __d( 'contactpartenaire', 'Contactpartenaire.nom', true ), 'type' => 'select', 'options' => $listeContacts, 'empty' => true ),
					'Search.Partenaire.codepartenaire' => array( 'label' => __d( 'partenaire', 'Partenaire.codepartenaire', true ) ),
					'Search.Actioncandidat.themecode' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.themecode', true ) ),
					'Search.Actioncandidat.codefamille' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.codefamille', true ) ),
					'Search.Actioncandidat.numcodefamille' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.numcodefamille', true ) )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>
	<?php if( isset( $search ) ):?>
		<?php if( is_array( $search ) && count( $search ) > 0  ):?>

<h2 class="noprint">Résultats de la recherche</h2>
<br>
	<div id="tabbedWrapper" class="tabs">
			<?php
				foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
					echo $form->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
				}
			?>
			<div id="global">
				<h2 class="title">Global</h2>
				<?php $pagination = $xpaginator->paginationBlock( 'Actioncandidat', $this->passedArgs ); ?>
				<?php echo $pagination;?>
				<?php
					echo $default2->index(
						$search,
						array(
							'Actioncandidat.name',
							'Actioncandidat.codeaction' => array( 'type' => 'text' ),
							'Chargeinsertion.nom_complet' => array( 'label' => 'Chargé d\'insertion', 'type' => 'text' ),
							'Secretaire.nom_complet' => array( 'label' => 'Secrétaire', 'type' => 'text' ),
							'Actioncandidat.lieuaction',
							'Actioncandidat.cantonaction',
							'Actioncandidat.ddaction',
							'Actioncandidat.dfaction',
							'Actioncandidat.nbpostedispo',
							'Actioncandidat.nbheuredispo',
							'Contactpartenaire.nom_candidat',
							'Contactpartenaire.nom_candidat' => array( 'type' => 'text' ),
							'Contactpartenaire.numtel',
							'Contactpartenaire.numfax',
							'Contactpartenaire.email',
							'Partenaire.libstruc',
							'Partenaire.codepartenaire',
							'Partenaire.adresse' => array( 'type' => 'text' ),
// 							'Partenaire.numvoie',
// 							'Partenaire.typevoie',
// 							'Partenaire.nomvoie',
// 							'Partenaire.compladr',
							'Partenaire.numtel',
		// 					'Partenaire.numfax',
		// 					'Partenaire.email',
// 							'Partenaire.codepostal',
// 							'Partenaire.ville',
							'Fichiermodule.nb_fichiers_lies' => array( 'label' => 'Nb fichiers liés', 'type' => 'integer' )
						),
						array(
							'cohorte' => false,
							'actions' => array(
								'Actionscandidats::view' => array( 'url' => array( 'controller' => 'offresinsertion', 'action' => 'view', '#Actioncandidat.id#' ) )
							),
							'options' => $options
						)
					);
				?>
				<?php echo $pagination;?>
				</div>
				<div id="actioncandidat">
					<h2 class="title">Actions</h2>
					<?php
						echo $default2->index(
							$search,
							array(
								'Actioncandidat.name',
								'Actioncandidat.codeaction' => array( 'type' => 'text' ),
								'Chargeinsertion.nom_complet' => array( 'label' => 'Chargé d\'insertion', 'type' => 'text' ),
								'Secretaire.nom_complet' => array( 'label' => 'Secrétaire', 'type' => 'text' ),
								'Actioncandidat.lieuaction',
								'Actioncandidat.cantonaction',
								'Actioncandidat.ddaction',
								'Actioncandidat.dfaction',
								'Actioncandidat.nbpostedispo',
								'Actioncandidat.nbheuredispo',
								'Fichiermodule.nb_fichiers_lies' => array( 'label' => 'Nb fichiers liés', 'type' => 'integer' )
							),
							array(
								'cohorte' => false,
								'actions' => array(
									'Actionscandidats::view' => array( 'url' => array( 'controller' => 'offresinsertion', 'action' => 'view', '#Actioncandidat.id#' ) )
								),
								'options' => $options
							)
						);
					?>
				</div>
				<div id="partenaires">
					<h2 class="title">Partenaires</h2>
					<?php
						echo $default2->index(
							$search,
							array(
								'Partenaire.libstruc',
								'Partenaire.codepartenaire',
								'Partenaire.adresse' => array( 'type' => 'text' ),
// 								'Partenaire.numvoie',
// 								'Partenaire.typevoie',
// 								'Partenaire.nomvoie',
// 								'Partenaire.compladr',
								'Partenaire.numtel',
								'Partenaire.numfax',
// 								'Partenaire.email',
// 								'Partenaire.codepostal',
// 								'Partenaire.ville'
							),
							array(
								'cohorte' => false,
								'actions' => array(
									'Actionscandidats::view' => array( 'url' => array( 'controller' => 'offresinsertion', 'action' => 'view', '#Actioncandidat.id#' ) )
								),
								'options' => $options
							)
						);
					?>
				</div>
				<div id="contacts">
					<h2 class="title">Contacts partenaires</h2>
					<?php
						echo $default2->index(
							$search,
							array(
								'Contactpartenaire.nom_candidat',
								'Contactpartenaire.nom_candidat' => array( 'type' => 'text' ),
								'Contactpartenaire.numtel',
								'Contactpartenaire.numfax',
								'Contactpartenaire.email'
							),
							array(
								'cohorte' => false,
								'actions' => array(
									'Actionscandidats::view' => array( 'url' => array( 'controller' => 'offresinsertion', 'action' => 'view', '#Actioncandidat.id#' ) )
								),
								'options' => $options
							)
						);
					?>
				</div>
			</div>
			<?php else:?>
				<p class="notice">Vos critères n'ont retourné aucune information.</p>
			<?php endif?>
		<?php endif;?>
	</div>

<!-- *********************************************************************** -->

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
?>
<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
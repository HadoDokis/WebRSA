<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

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
				<table>
					<colgroup span="10" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup span="4" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup span="4" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup />
					<colgroup />
					<thead>
						<tr>
							<th colspan="10">Action de candidature</th>
							<th colspan="4">Contact</th>
							<th colspan="5">Partenaire/Prestataire</th>
							<th>Actions</th>
						</tr>
						<tr>
							<th><?php echo $xpaginator->sort( 'Intitulé de l\'action', 'Actioncandidat.name' );?></th>
							<th><?php echo $xpaginator->sort( 'Code de l\'action', 'Actioncandidat.codeaction' );?></th>
							<th><?php echo $xpaginator->sort( 'Chargé d\'insertion', 'Chargeinsertion.nom_complet' );?></th>
							<th><?php echo $xpaginator->sort( 'Secrétaire', 'Secretaire.nom_complet' );?></th>
							<th><?php echo $xpaginator->sort( 'Ville', 'Actioncandidat.lieuaction' );?></th>
							<th><?php echo $xpaginator->sort( 'Canton', 'Actioncandidat.cantonaction' );?></th>
							<th><?php echo $xpaginator->sort( 'Début de l\'action', 'Actioncandidat.ddaction' );?></th>
							<th><?php echo $xpaginator->sort( 'Fin de l\'action', 'Actioncandidat.dfaction' );?></th>
							<th><?php echo $xpaginator->sort( 'Nombre de postes disponibles', 'Actioncandidat.nbpostedispo' );?></th>
							<th><?php echo $xpaginator->sort( 'Nombre d\'heures disponibles', 'Actioncandidat.nbheuredispo' );?></th>

							<th><?php echo $xpaginator->sort( 'Nom du contact', 'Contactpartenaire.nom_candidat' );?></th>
							<th><?php echo $xpaginator->sort( 'N° de téléphone du contact', 'Contactpartenaire.numtel' );?></th>
							<th><?php echo $xpaginator->sort( 'N° de fax', 'Contactpartenaire.numfax' );?></th>
							<th><?php echo $xpaginator->sort( 'Email du contact', 'Contactpartenaire.email' );?></th>

							<th><?php echo $xpaginator->sort( 'Libellé du partenaire', 'Partenaire.libstruc' );?></th>
							<th><?php echo $xpaginator->sort( 'Code du partenaire', 'Partenaire.codepartenaire' );?></th>
							<th><?php echo $xpaginator->sort( 'Adresse du partenaire', 'Partenaire.adresse' );?></th>
							<th><?php echo $xpaginator->sort( 'N° de téléphone du partenaire', 'Partenaire.numtel' );?></th>

							<th>Nb de fichiers liés</th>

							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
						$urlParams = Set::flatten( $this->data, '__' );
						foreach( $search as $result ) {
							echo $xhtml->tableCells(
								array(
									Set::classicExtract( $result, 'Actioncandidat.name' ),
									Set::classicExtract( $result, 'Actioncandidat.codeaction' ),
									Set::classicExtract( $result, 'Chargeinsertion.nom_complet' ),
									Set::classicExtract( $result, 'Secretaire.nom_complet' ),
									Set::classicExtract( $result, 'Actioncandidat.lieuaction' ),
									Set::classicExtract( $result, 'Actioncandidat.cantonaction' ),
									date_short( Set::classicExtract( $result, 'Actioncandidat.ddaction' ) ),
									date_short( Set::classicExtract( $result, 'Actioncandidat.dfaction' ) ),
									Set::classicExtract( $result, 'Actioncandidat.nbpostedispo' ),
									Set::classicExtract( $result, 'Actioncandidat.nbheuredispo' ),
									Set::classicExtract( $result, 'Contactpartenaire.nom_candidat' ),
									Set::classicExtract( $result, 'Contactpartenaire.numtel' ),
									Set::classicExtract( $result, 'Contactpartenaire.numfax' ),
									Set::classicExtract( $result, 'Contactpartenaire.email' ),
									Set::classicExtract( $result, 'Partenaire.libstruc' ),
									Set::classicExtract( $result, 'Partenaire.codepartenaire' ),
									Set::classicExtract( $result, 'Partenaire.adresse' ),
									Set::classicExtract( $result, 'Partenaire.numtel' ),
									Set::classicExtract( $result, 'Fichiermodule.nb_fichiers_lies' ),
									$xhtml->filelink(
										'Voir',
										array_merge(
											array(
												'controller' => 'offresinsertion', 'action' => 'view', Set::classicExtract( $result, 'Actioncandidat.id' )
											),
											$urlParams
										)
									)
								),
								array( 'class' => 'odd' ),
								array( 'class' => 'even' )
							);

						}
					?>
					</tbody>
				</table>
				<?php echo $pagination;?>
                <ul class="actionMenu">
                    <li><?php
                        echo $xhtml->exportLink(
                            'Télécharger le tableau',
                            array( 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
                        );
                    ?></li>
                </ul>
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
							'Partenaire.numtel',
							'Partenaire.numfax'
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
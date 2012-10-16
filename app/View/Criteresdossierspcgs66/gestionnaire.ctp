<?php
    $this->pageTitle = 'Gestionnaire de dossiers PCG';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>

<?php echo $this->Xform->create( 'Criteredossierpcg66', array( 'type' => 'post', 'action' => $this->action,  'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && isset( $this->request->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );?>

        <fieldset>
			<?php echo $this->Xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

            <legend>Informations par Gestionnaire</legend>
            <?php

                echo $this->Default2->subform(
                    array(
						'Dossierpcg66.user_id' => array(  'label' => __d( 'dossierpcg66', 'Dossierpcg66.user_id' ), 'options' => $gestionnaire, 'empty' => true )
                    ),
                    array(
                        'options' => $options
                    )
                );
                echo $this->Search->etatDossierPCG66( $etatdossierpcg );
                
                echo $this->Default2->subform(
                    array(
						'Dossierpcg66.dossierechu' => array(  'label' => 'Dossier échu', 'type' => 'checkbox' )
                    )
                );
            ?>
        </fieldset>

    <div class="submit noprint">
        <?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>



<?php echo $this->Xform->end();?>
<?php $pagination = $this->Xpaginator->paginationBlock( 'Dossierpcg66', $this->passedArgs ); ?>

	<?php if( isset( $criteresdossierspcgs66 ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criteresdossierspcgs66 ) && count( $criteresdossierspcgs66 ) > 0  ):?>
	<?php echo $pagination;?>
		<?php
			foreach( Set::flatten( $this->request->data['Search'] ) as $filtre => $value  ) {
				echo $this->Form->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
			}
		?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Origine de la PDO', 'Dossierpcg66.originepdo_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Type de dossier', 'Dossierpcg66.typepdo_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date de réception', 'Dossierpcg66.datereceptionpdo' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Gestionnaire', 'Dossierpcg66.user_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nb de propositions de décisions', 'Dossierpcg66.nbpropositions' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nb de traitements PCGs', 'Personnepcg66.nbtraitements' );?></th>
					<th>Types de traitements</th>
					<th><?php echo $this->Xpaginator->sort( 'Etat du dossier', 'Dossierpcg66.etatdossierpcg' );?></th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criteresdossierspcgs66 as $index => $criteredossierpcg66 ) {

						$datetransmission = '';
						if( $criteredossierpcg66['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
							$datetransmission = ' le '.date_short( Set::classicExtract( $criteredossierpcg66, 'Decisiondossierpcg66.datetransmissionop' ) );
						}


						//Liste des différents traitements PCGs de la personne PCG
						$traitementspcgs66 = '';
						foreach( $criteredossierpcg66['Dossierpcg66']['listetraitements'] as $key => $traitement ) {
							if( !empty( $traitement ) ) {
								$traitementspcgs66 .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.Set::enum( $traitement, $options['Traitementpcg66']['typetraitement'] ).'</li></ul>';
							}
						}

						$etatdosrsaValue = Set::classicExtract( $criteredossierpcg66, 'Situationdossierrsa.etatdosrsa' );
						$etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';

						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.h( $etatDossierRSA ).'</td>
								</tr>
								<tr>
									<th>Commune de naissance</th>
									<td>'.h( $criteredossierpcg66['Personne']['nomcomnai'] ).'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.h( date_short( $criteredossierpcg66['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $criteredossierpcg66['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $criteredossierpcg66['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.h( $criteredossierpcg66['Dossier']['matricule'] ).'</td>
								</tr>

							</tbody>
						</table>';

						echo $this->Xhtml->tableCells(
							array(
								h( Set::classicExtract( $criteredossierpcg66, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.nom' ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
								h( $this->Locale->date( 'Locale->date',  Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.datereceptionpdo' ) ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
								h( $criteredossierpcg66['Dossierpcg66']['nbpropositions'] ),
								h( $criteredossierpcg66['Personnepcg66']['nbtraitements'] ),
								$traitementspcgs66,
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission ),
								$this->Xhtml->viewLink(
									'Voir',
									array( 'controller' => 'dossierspcgs66', 'action' => 'edit', Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' ) )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					}
				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criteresdossierspcgs66', 'action' => 'exportcsv' ) + Set::flatten( $this->request->data, '__' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucune information.</p>
	<?php endif?>
<?php endif?>
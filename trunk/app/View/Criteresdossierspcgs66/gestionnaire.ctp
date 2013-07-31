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
                
                echo $this->Xform->input( 'Decisiondossierpcg66.org_id', array( 'label' => 'Organismes auxquels sont transmis les dossiers', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $listorganismes, 'empty' => false ) );
                
                 echo $this->Default2->subform(
					array(
						'Dossierpcg66.originepdo_id' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.originepdo_id' ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
						'Dossierpcg66.typepdo_id' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.typepdo_id' ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
						'Dossierpcg66.orgpayeur' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.orgpayeur' ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
                        'Decisiondossierpcg66.decisionpdo_id' => array( 'label' => 'Décision', 'type' => 'select', 'options' => $decisionpdo, 'empty' => true )
					),
					array(
						'options' => $options
					)
				);
                 
                echo $this->Form->input( 'Traitementpcg66.situationpdo_id', array( 'label' => 'Motif concernant la personne', 'type' => 'select', 'options' => $motifpersonnepcg66, 'empty' => true ) );
                echo $this->Form->input( 'Traitementpcg66.statutpdo_id', array( 'label' => 'Statut concernant la personne', 'type' => 'select', 'options' => $statutpersonnepcg66, 'empty' => true ) );
		
                
                echo $this->Default2->subform(
                    array(
						'Dossierpcg66.dossierechu' => array(  'label' => 'Dossier échu', 'type' => 'checkbox' )
                    )
                );
                
                echo $this->Search->natpf( $natpf );
                echo $this->Form->input('Dossierpcg66.exists', array( 'label' => 'Corbeille pleine ?', 'type' => 'select', 'options' => $exists, 'empty' => true ) );
                echo $this->Xform->input( 'Decisiondossierpcg66.nbproposition', array( 'label' => 'Nombre de propositions de décision') );
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
			foreach( Hash::flatten( $this->request->data['Search'] ) as $filtre => $value  ) {
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
					<th><?php echo $this->Xpaginator->sort( 'Date de d\'échéance', 'Traitementpcg66.dateecheance' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Gestionnaire', 'Dossierpcg66.user_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nb propo. décisions', 'Dossierpcg66.nbpropositions' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nb traitements', 'Personnepcg66.nbtraitements' );?></th>
					<th>Types de traitements</th>
					<th><?php echo $this->Xpaginator->sort( 'Etat du dossier', 'Dossierpcg66.etatdossierpcg' );?></th>
                    <th><?php echo $this->Xpaginator->sort( 'Décision sur le dossier', 'Decisiondossierpcg66.decisionpdo_id' );?></th>
					<th>Motif(s) personne</th>
                    <th>Statut(s) personne</th>
                    <th>Nb de fichiers dans la corbeille</th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criteresdossierspcgs66 as $index => $criteredossierpcg66 ) {

						$datetransmission = '';
                        
                        // Liste des organismes auxquels on transmet le dossier
                        $orgs =  Set::classicExtract( $criteredossierpcg66, 'Decisiondossierpcg66.organismes' );
                        $orgs = implode( ', ', $orgs  );
						if( $criteredossierpcg66['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
                            $datetransmission = ' à '.$orgs.' le '.date_short( Set::classicExtract( $criteredossierpcg66, 'Decisiondossierpcg66.datetransmissionop' ) );
                        }
                        else if( $criteredossierpcg66['Dossierpcg66']['etatdossierpcg'] == 'atttransmisop' ){
                            $datetransmission = ' à '.$orgs; 
						}

                        
						//Liste des différents traitements PCGs de la personne PCG
						$traitementspcgs66 = '';
						foreach( $criteredossierpcg66['Dossierpcg66']['listetraitements'] as $key => $traitement ) {
							if( !empty( $traitement ) ) {
								$traitementspcgs66 .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.Set::enum( $traitement, $options['Traitementpcg66']['typetraitement'] ).'</li></ul>';
							}
						}
                        
                        //Liste des différents traitements PCGs de la personne PCG
						$echeances = '';
						foreach( $criteredossierpcg66['Dossierpcg66']['dateecheance'] as $key => $echeance ) {
							if( !empty( $echeance ) ) {
								$echeances .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.date_short( $echeance ).'</li></ul>';
							}
						}

						$etatdosrsaValue = Set::classicExtract( $criteredossierpcg66, 'Situationdossierrsa.etatdosrsa' );
						$etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';
                        
                        
                        $differentsMotifs = '';
						foreach( $criteredossierpcg66['Personnepcg66']['listemotifs'] as $key => $motif ) {
							if( !empty( $motif ) ) {
								$differentsMotifs .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$motif.'</li></ul>';
							}
						}
                        
                        $differentsStatuts = '';
						foreach( $criteredossierpcg66['Personnepcg66']['listestatuts'] as $key => $statut ) {
							if( !empty( $statut ) ) {
								$differentsStatuts .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
							}
						}


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
//debug( $criteredossierpcg66 );
						echo $this->Xhtml->tableCells(
							array(
								h( Set::classicExtract( $criteredossierpcg66, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.nom' ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
								$echeances,//h( $this->Locale->date( 'Locale->date',  Set::classicExtract( $criteredossierpcg66, 'Traitementpcg66.dateecheance' ) ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
								h( $criteredossierpcg66['Dossierpcg66']['nbpropositions'] ),
								h( $criteredossierpcg66['Personnepcg66']['nbtraitements'] ),
								$traitementspcgs66,
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission ),
                                h( $criteredossierpcg66['Decisionpdo']['libelle'] ),
                                $differentsMotifs,
                                $differentsStatuts,
								h( $criteredossierpcg66['Fichiermodule']['nb_fichiers_lies'] ),
								$this->Xhtml->viewLink(
									'Voir',
									array( 'controller' => 'dossierspcgs66', 'action' => 'index', Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.foyer_id' ) )
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
					array( 'controller' => 'criteresdossierspcgs66', 'action' => 'exportcsv', 'searchGestionnaire'  ) + Hash::flatten( $this->request->data, '__' ),
					$this->Permissions->check( 'criteresdossierspcgs66', 'exportcsv' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucune information.</p>
	<?php endif?>
<?php endif?>
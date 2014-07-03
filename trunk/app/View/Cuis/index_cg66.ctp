<?php
	$this->pageTitle = __d( 'cui', "Cuis::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>

<?php echo $this->Xhtml->tag( 'h1', $this->pageTitle );?>
<?php echo $this->element( 'ancien_dossier' );?>

<?php if( $this->Permissions->checkDossier( 'cuis', 'add', $dossierMenu ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter un CUI',
				array( 'controller' => 'cuis', 'action' => 'add', $personne_id )
			).' </li>';
		?>
	</ul>
<?php endif;?>
<?php if( !empty( $alerteRsaSocle ) ):?>
	<p class="error">Cette personne ne possède pas de rSA Socle.</p>
<?php endif;?>

<?php if( !empty( $alerteTitreSejour ) ):?>

    <?php if( is_null( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] ) && is_null( $alerteTitreSejour['Cui']['nbMoisAvantFinCui'] ) ) :?>
    <?php else:?>
        <?php if( ( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] == 0 ) || ( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] == 0 ) ) :?>
            <p class="notice">Le titre de séjour de cet allocataire va se terminer dans moins d'1 mois.</p>
        <?php endif;?>
        <!-- Information présente dans la table titressejour -->
        <?php if( ( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] > 0 ) && ( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] < 12 ) ) :?>
            <p class="notice">Le titre de séjour de cet allocataire va se terminer dans <?php echo $alerteTitreSejour['Titresejour']['nbMoisAvantFin'];?> mois.</p>
        <!-- Information présente dans le formulaire CUI-->
        <?php elseif( ( $alerteTitreSejour['Cui']['nbMoisAvantFinCui'] > 0 ) && ( $alerteTitreSejour['Cui']['nbMoisAvantFinCui'] < 12 ) ) :?>
            <p class="notice">Le titre de séjour de cet allocataire va se terminer dans <?php echo $alerteTitreSejour['Cui']['nbMoisAvantFinCui'];?> mois.</p>
        <?php endif;?>

        <?php if( ( $alerteTitreSejour['Cui']['nbMoisAvantFinCui'] < 0 ) || ( $alerteTitreSejour['Titresejour']['nbMoisAvantFin'] < 0 ) ):?>
            <p class="notice">Le titre de séjour de cet allocataire a expiré.</p>
        <?php endif;?>
    <?php endif;?>
<?php endif;?>

    <?php if( empty( $persreferent ) ) :?>
        <p class="error">Aucun référent actif n'est lié au parcours de cette personne.</p>
    <?php endif;?>

<?php if( empty( $cuis ) ):?>
	<p class="notice">Cette personne ne possède pas encore de CUI.</p>
<?php endif;?>

<?php if( !empty( $cuis ) ):?>
<table class="tooltips default2" id="searchResults">
	<thead>
		<tr>
			<th>Type de CUI</th>
			<th>Date du contrat</th>
			<th>Secteur</th>
			<th>Employeur</th>
			<th>Date de début de prise en charge</th>
			<th>Date de fin de prise en charge</th>
			<th>Décision pour le CUI</th>
            <th>Position du CUI</th>
			<th>Date de validation</th>
			<th colspan="13" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $cuis as $index => $cui ):?>
			<?php

				$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
						<tbody>
							<tr>
								<th>Raison annulation</th>
								<td>'.$cui['Cui']['motifannulation'].'</td>
							</tr>
						</tbody>
					</table>';
                
                $positioncui66 = Set::enum( Set::classicExtract( $cui, 'Cui.positioncui66' ), $options['Cui']['positioncui66'] );
                if( ( ( $cui['Cui']['positioncui66'] == 'dossierrecu' ) && !empty( $cui['Cui']['datedossierrecu'] ) ) || ( ( $cui['Cui']['positioncui66'] == 'dossiereligible' ) && !empty( $cui['Cui']['datedossiereligible'] ) ) ) {
                    $positioncui66 = $positioncui66.' le '.$this->Locale->date( 'Date::short', $cui['Cui']['datedossierrecu'] );
                }

				echo $this->Xhtml->tableCells(
					array(
						h( Set::enum( Set::classicExtract( $cui, 'Cui.typecui' ), $options['Cui']['typecui'] ) ),
						h( date_short( Set::classicExtract( $cui, 'Cui.datecontrat' ) ) ),
						h( Set::classicExtract( $cui, 'Secteurcui.name' ) ),
						h( Set::classicExtract( $cui, 'Cui.nomemployeur' ) ),
						h( date_short( Set::classicExtract( $cui, 'Cui.datedebprisecharge' ) ) ),
						h( date_short( Set::classicExtract( $cui, 'Cui.datefinprisecharge' ) ) ),
						h( Set::enum( Set::classicExtract( $cui, 'Cui.decisioncui' ), $options['Cui']['decisioncui'] ) ),
                        h( $positioncui66 ),
						h( date_short( Set::classicExtract( $cui, 'Cui.datevalidationcui' ) ) ),
						$this->Default2->button(
							'view',
							array( 'controller' => 'cuis', 'action' => 'view', $cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'view', $dossierMenu ) == 1 )
								)
							)
						),
						$this->Default2->button(
							'edit',
							array( 'controller' => 'cuis', 'action' => 'edit', $cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'edit', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
						$this->Default2->button(
							'email',
							array( 'controller' => 'cuis', 'action' => 'maillink', $cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'maillink', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
						$this->Default2->button(
							'proposition',
							array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'proposdecisionscuis66', 'propositioncui', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
						$this->Default2->button(
							'valider',
							array( 'controller' => 'decisionscuis66', 'action' => $cui['Cui']['action'],
							$cui['Cui']['id'] ),
							array(
                                'label' => 'Décision',
								'enabled' => (
									( $this->Permissions->checkDossier( 'decisionscuis66', 'add', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
									&& ( Set::classicExtract( $cui, 'Propodecisioncui66.nb_proposition' ) != '0' )
								)
							)
						),
						$this->Default2->button(
							'print',
							array( 'controller' => 'cuis', 'action' => 'impression',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'impression', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
                        $this->Default2->button(
                            'accompagnement',
                            array( 'controller' => 'accompagnementscuis66', 'action' => 'index',
                                $cui['Cui']['id'] ),
                            array(
                                'label' => 'Accompagnement',
                                'enabled' => (
                                    ( $this->Permissions->checkDossier( 'accompagnementscuis66', 'index', $dossierMenu ) == 1 )
                                    && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
                                )
                            )
                        ),
						$this->Default2->button(
							'suspension',
							array( 'controller' => 'suspensionscuis66', 'action' => 'index',
							$cui['Cui']['id'] ),
							array(
								'label' => 'Suspension',
								'enabled' => (
									( $this->Permissions->checkDossier( 'suspensionscuis66', 'index', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
                        $this->Default2->button(
                            'rupture',
                            array( 'controller' => 'rupturescuis66', 'action' => 'edit',
                                $cui['Cui']['id'] ),
                            array(
                                'label' => 'Rupture',
                                'enabled' => (
                                    ( $this->Permissions->checkDossier( 'rupturescuis66', 'edit', $dossierMenu ) == 1 )
                                    && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
                                    && ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'rupture' )

                                )
                            )
                        ),
						$this->Default2->button(
							'cancel',
							array( 'controller' => 'cuis', 'action' => 'cancel',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'cancel', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $cui, 'Cui.positioncui66' ) != 'annule' )
								)
							)
						),
						$this->Default2->button(
							'delete',
							array( 'controller' => 'cuis', 'action' => 'delete',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'delete', $dossierMenu ) == 1 )
								),
                                'confirm' => 'Confirmer la suppression ?'
							)
						),
						$this->Default2->button(
							'filelink',
							array( 'controller' => 'cuis', 'action' => 'filelink',
							$cui['Cui']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'cuis', 'filelink', $dossierMenu ) == 1 )
								)
							)
						),
						h( '('.Set::classicExtract( $cui, 'Fichiermodule.nb_fichiers_lies' ).')' ),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
					),
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
			?>
		<?php endforeach;?>
	</tbody>
</table>
<?php  endif;?>
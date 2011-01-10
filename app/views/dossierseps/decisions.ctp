<h1><?php echo $this->pageTitle = 'Liste des décisions des dossiers d\'EP';?></h1>

<?php if( empty( $dossierseps ) ):?>
	<p class="notice">Il n'y a pas encore de décision visible.</p>
<?php else:?>
	<?php
		$paginator->options( array( 'url' => $this->passedArgs ) );
		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );

		$pagination = '<p>'.$paginator->counter( $params ).'</p>';
		$pagination .= '<p>'.implode(
			' ',
			array(
				$paginator->first( '<<' ),
				$paginator->prev( '<' ),
				$paginator->numbers(),
				$paginator->next( '>' ),
				$paginator->last( '>>' )
			)
		).'</p>';

		echo $pagination;
	?>
	<table>
		<thead>
			<tr>
				<th><?php echo $paginator->sort( 'Dossier EP', 'Dossierep.id' );?></th>
				<th>Nom du demandeur</th>
				<th>Adresse</th>
				<th>Date de naissance</th>
				<th><?php echo $paginator->sort( 'Thème du dossier EP', 'Dossierep.themeep' );?></th>
				<th>Date de décision</th>
				<th>Décision</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach( $dossierseps as $i => $dossierep ) {
			// FIXME: voir contrôleur -> plus générique
			$decision = null;
			$datedecision = null;

			// CG 66
			if( $dossierep['Dossierep']['themeep'] == 'defautsinsertionseps66' ) {
				$decision = Set::enum(
					@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][0]['decision'],
					$decisions['Defautinsertionep66']
				);
				$datedecision = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][0]['modified'];
			}
			else if( $dossierep['Dossierep']['themeep'] == 'saisinesepsbilansparcours66' ) {
				$decision = Set::enum(
					@$dossierep['Saisineepbilanparcours66']['Nvsrepreorient66'][0]['decision'],
					$decisions['Saisineepbilanparcours66']
				);
				$datedecision = @$dossierep['Saisineepbilanparcours66']['Nvsrepreorient66'][0]['modified'];
			}
			else if( $dossierep['Dossierep']['themeep'] == 'saisinesepdspdos66' ) {
				$decision = @$dossierep['Saisineepdpdo66']['Nvsepdpdo66'][0]['Decisionpdo']['libelle'];
				$datedecision = @$dossierep['Saisineepdpdo66']['Nvsepdpdo66'][0]['modified'];
			}
			// CG 93
			else if( $dossierep['Dossierep']['themeep'] == 'saisinesepsreorientsrs93' ) {
				$decision = Set::enum(
					@$dossierep['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['decision'],
					$decisions['Saisineepreorientsr93']
				);
				$datedecision = @$dossierep['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['modified'];
			}
			else if( $dossierep['Dossierep']['themeep'] == 'nonrespectssanctionseps93' ) {
				$decision = Set::enum(
					@$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0]['decision'],
					$decisions['Nonrespectsanctionep93']
				);
				$datedecision = @$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0]['modified'];
			}

			echo $xhtml->tableCells(
				array(
					$dossierep['Dossierep']['id'],
					implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
					implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
					$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
					Set::enum( $dossierep['Dossierep']['themeep'], $options['Dossierep']['themeep'] ),
					$locale->date( __( 'Locale->date', true ), $datedecision ),
					$decision
				)
			);
		}
	?>
		</tbody>
	</table>
	<?php echo $pagination;?>
<?php endif;?>

<?php
/*debug( $dossierseps );
debug( $options );*/
?>
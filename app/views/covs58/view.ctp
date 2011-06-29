<h1><?php	echo $this->pageTitle = 'Affichage commission de COV'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php
		if( $cov58['Cov58']['etatcov'] == 'cree' ) {
			echo '<li>'.$xhtml->editLink(
				__d( 'cov58', 'Cov58.edit', true ),
				array( 'controller' => 'covs58', 'action' => 'edit', $cov58['Cov58']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> Modifier</span></li>';
		}

		if( $countDossiers > 0 ) {
			echo '<li>'.$xhtml->link(
				__d( 'cov58','Covs58::ordredujour', true ),
				array( 'controller' => 'covs58', 'action' => 'ordredujour', $cov58['Cov58']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'cov58','Covs58::ordredujour',true ).'</span></li>';
		}

		if( $cov58['Cov58']['etatcov'] != 'finalise' && $countDossiers > 0 ) {
			echo '<li>'.$xhtml->link(
				__d( 'cov58','Covs58::decisioncov',true ),
				array( 'controller' => 'covs58', 'action' => 'decisioncov', $cov58['Cov58']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'cov58','Covs58::decisioncov',true ).'</span></li>';
		}

		if( $cov58['Cov58']['etatcov'] == 'finalise' ) {
			echo '<li>'.$xhtml->link(
				__d( 'cov58','Covs58::impressionpv',true ),
				array( 'controller' => 'covs58', 'action' => 'impressionpv', $cov58['Cov58']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'cov58','Covs58::impressionpv',true ).'</span></li>';
		}
		
	?>
	</ul>
	<table>
		<tbody>
			<tr class="odd">
				<th><?php echo __d('cov58', 'Cov58.datecommission', true);?></th>
				<td><?php echo isset( $cov58['Cov58']['datecommission'] ) ? strftime( '%d/%m/%Y à %H:%M', strtotime( $cov58['Cov58']['datecommission'])) : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo __d('cov58', 'Cov58.sitecov58_id', true);?></th>
				<td><?php echo isset( $cov58['Sitecov58']['name'] ) ? $cov58['Sitecov58']['name'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo __d('cov58', 'Cov58.lieu', true);?></th>
				<td><?php echo isset( $cov58['Cov58']['lieu'] ) ? $cov58['Cov58']['lieu'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo __d('cov58', 'Cov58.observation', true);?></th>
				<td><?php echo isset( $cov58['Cov58']['observation'] ) ? $cov58['Cov58']['observation'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo __d('cov58', 'Cov58.etatcov', true);?></th>
				<td><?php echo isset( $cov58['Cov58']['etatcov'] ) ? __d ( 'cov58', 'ENUM::ETATCOV::'.$cov58['Cov58']['etatcov'] ) : null ;?></td>
			</tr>
		</tbody>
	</table>
</div>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="dossiers">
		<h2 class="title">Liste des dossiers</h2>
		<ul class="actionMenu">
			<?php
				if( $cov58['Cov58']['etatcov'] == 'cree' ) {
					echo '<li>'.$xhtml->affecteLink(
						'Affecter les dossiers',
						array( 'controller' => 'dossierscovs58', 'action' => 'choose', Set::classicExtract( $cov58, 'Cov58.id' ) )
					).' </li>';
				}
				else {
					echo '<li><span class="disabled"> Affecter les dossiers</span></li>';
				}
			?>
		</ul>
		<div id="dossierscovs">
			<?php
				foreach( $themes as $theme ) {
					if( $theme == 'proposorientationscovs58' ){
						$controller = 'orientsstructs';
					}
					else if( $theme == 'proposcontratsinsertioncovs58' ){
						$controller = 'contratsinsertion';
					}
					$class = Inflector::classify( $theme );
					echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossiercov58', 'ENUM::THEMECOV::'.$theme, true )."</h3>";
					echo $default2->index(
						$dossiers[$class],
						array(
							'Personne.qual',
							'Personne.nom',
							'Personne.prenom',
							'Personne.dtnai',
							'Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
// 							$class.'.0.datedemande',
							'Dossiercov58.etapecov'
						),
						array(
							'actions' => array(
								'Dossierscovs58::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => $controller, 'action' => 'index', '#Personne.id#' ) ),
								'Dossierscovs58::impressiondecision' => array( 'label' => 'Imprimer la décision', 'url' => array( 'controller' => 'covs58', 'action' => 'impressiondecision', '#Dossiercov58.id#' ), 'disabled' => '"#Dossiercov58.etapecov#" != "finalise" || "#Themecov58.name#" == "proposcontratsinsertioncovs58"' )
							),
							'options' => $options
						)
					);
					echo "</div>";
				}
			?>
		</div>
	</div>
</div>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'dossierscovs', 3 );
</script>

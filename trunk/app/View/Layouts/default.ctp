<?php
	/**
	 *
	 * PHP 5
	 *
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 * @link          http://cakephp.org CakePHP(tm) Project
	 * @package       Cake.View.Layouts
	 * @since         CakePHP(tm) v 0.10.0.1076
	 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php
				if( isset( $this->pageTitle ) && !empty( $this->pageTitle ) ) {
					echo $this->pageTitle;
				}
				else {
					echo $title_for_layout;
				}
			?>
		</title>
		<?php
			if( Configure::read( 'debug' ) ) {
				echo $this->Xhtml->css( array( 'all.reset' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $this->Xhtml->css( array( 'all.base' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $this->Xhtml->css( array( 'screen.generic' ), 'stylesheet', array( 'media' => 'screen,presentation' ) );
				echo $this->Xhtml->css( array( 'print.generic' ), 'stylesheet', array( 'media' => 'print' ) );
				echo $this->Xhtml->css( array( 'menu' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $this->Xhtml->css( array( 'popup' ), 'stylesheet', array( 'media' => 'all' ) );

				echo $this->Html->script( 'prototype' );
				echo $this->Html->script( 'tooltip.prototype' );
				echo $this->Html->script( 'webrsa.common.prototype' );
			}
			else {
				echo $this->Xhtml->css( array( 'webrsa' ), 'stylesheet' );
				echo $this->Html->script( 'webrsa' );
			}

			echo $this->fetch( 'meta' );
			echo $this->fetch( 'css' );
			echo $this->fetch( 'script' );
		?>

		<!-- TODO: à la manière de cake, dans les vues qui en ont besoin -->
		<script type="text/javascript">
		<!--//--><![CDATA[//><!--
			// prototype
			document.observe( "dom:loaded", function() {
				<?php
					$backAllowed = true;

					$pagesBackNotAllowed = array(
						'Cohortesci::index',
						'Cohortes::nouvelles',
						'Cohortes::enattente',
						'Cohortespdos::avisdemande',
						'Recours::gracieux',
						'Recours::contentieux',
						'Contratsinsertion::valider',
						'Ajoutdossiers::wizard',
						'Ajoutdossiers::confirm',
						'Cohortesindus::index',
						'Users::login',
					);

					if( ( $this->action == 'add' ) || ( $this->action == 'edit' ) || ( $this->action == 'delete' ) || in_array( $this->name.'::'.$this->action, $pagesBackNotAllowed ) ) {
						$backAllowed = false;
					}
				?>
				<?php if( !$backAllowed && Configure::read( 'debug' ) == 0 ):?>
				window.history.forward();
				<?php endif;?>

				var baseUrl = '<?php echo Router::url( '/' );?>';
				<?php if ( isset( $urlmenu ) ) { ?>
					var urlmenu = '<?php echo $urlmenu ?>';
				<?php } else { ?>
					var urlmenu = null;
				<?php } ?>
				make_treemenus( baseUrl, <?php echo ( Configure::read( 'UI.menu.large' ) ? 'true' : 'false' );?>, urlmenu );
				make_folded_forms();
				mkTooltipTables();
				make_external_links();

				<?php if( isset( $useAlerteFinSession ) && $useAlerteFinSession ):?>
				if( '<?php echo $useAlerteFinSession;?>' ) {
					var sessionTime = parseInt('<?php echo readTimeout(); ?>');
					var warning5minutes = sessionTime - (5*60);
					setTimeout(alert5minutes, warning5minutes*1000);
					setTimeout(sessionEnd, sessionTime*1000);
				}
				<?php endif;?>
			} );

			<?php if( isset( $useAlerteFinSession ) && $useAlerteFinSession ):?>
			function alert5minutes() {
				$('alertEndSession').show();
			}

			function sessionEnd() {
				var baseUrl = '<?php echo Router::url( array( 'controller' => 'users', 'action' => 'logout' ) ); ?>';
				location.replace(baseUrl);
			}
			<?php endif;?>
		//--><!]]>
		</script>
		<!--[if IE]>
			<style type="text/css" media="screen, presentation">
				.treemenu { position: relative; }
				.treemenu, .treemenu *, #pageMenu, #pageWrapper { zoom: 1; }
			</style>
		<![endif]-->
	</head>
	<body class="<?php echo Inflector::underscore( $this->name )." {$this->action}";?>">
<?php if( isset( $useAlerteFinSession ) && $useAlerteFinSession ):?>
	<div id="alertEndSession" style="display: none;">
		<div id="popups" style="z-index: 1000;">
			<div id="popup_0">
				<div class="hideshow">
					<div class="fade" style="z-index: 31"></div>
					<div class="popup_block">
						<div class="popup">
							<a href="#" onclick="$('alertEndSession').hide(); return false;"><?php echo $this->Xhtml->image('icon_close.png', array('class' => 'cntrl', 'alt' => 'close')); ?></a>
							<div id="popup-content">Attention votre session expire dans 5 minutes.</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;?>

		<div id="pageWrapper"<?php if( Configure::read( 'UI.menu.large' ) ) { echo ' class="treemenu_large"'; } ?>>
			<div id="pageHeader">
				&nbsp;
			</div>
			<?php
				if( $this->Session->check( 'Auth.User.username' ) ) {
					echo $this->element( 'menu', array(), array( 'cache' => array( 'time' => '+1 day', 'key' => $this->Session->read( 'Auth.User.username' ), 'config' => 'views' ) ) );
					echo $this->element( 'cartouche' );
				}
			?>
			<div id="pageContent">
				<?php
				if( $this->Session->check( 'Auth.User.username' ) && $this->Permissions->check('search', 'search') ) {

					// Vérifi les permissions pour chaque moteurs de recherches et les met dans la liste déroulante
					$selectRecherche['dossiers_index'] =
							$this->Permissions->check('dossiers', 'index') ? 'Automatique' : null;

					$selectRecherche['criteres_index'] =
							$this->Permissions->check('criteres', 'index') ? 'Orientation' : null;

					$selectRecherche['criteresapres_all'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criteresapres', 'all') ? 'APREs' : null;

					$selectRecherche['criteresci_index'] =
							$this->Permissions->check('criteresci', 'index') ? 'CER' : null;

					$selectRecherche['criterescuis_index'] =
							$this->Permissions->check('criterescuis', 'index') ? 'CUI' : null;

					$selectRecherche['criteresentretiens_index'] =
							$this->Permissions->check('criteresentretiens', 'index') ? 'Entretiens' : null;

					$selectRecherche['criteresfichesscandidature_index'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criteresfichescandidature', 'index') ? 'Fiches de Candidature' : null;

					$selectRecherche['cohortesindus_index'] =
							$this->Permissions->check('cohortesindus', 'index') ? 'Indus' : null;

					$selectRecherche['dsps_index'] =
							$this->Permissions->check('dsps', 'index') ? 'DSPs' : null;

					$selectRecherche['criteresrdv_index'] =
							$this->Permissions->check('criteresrdv', 'index') ? 'Rendez-vous' : null;

					$selectRecherche['criteresdossierspcgs66_dossier'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criteresdossierspcgs66', 'dossier') ? 'Dossiers PCGs' : null;

					$selectRecherche['criterestraitementspcgs66_index'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criterestraitementspcgs66', 'index') ? 'Traitement PCGs' : null;

					$selectRecherche['criteresdossierspcgs66_gestionnaire'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criteresdossierspcgs66', 'gestionnaire') ? 'Gestionnaire PCGs' : null;

					$selectRecherche['criterespdos_nouvelles'] =
							( Configure::read( 'Cg.departement' ) != 66 ) &&
							$this->Permissions->check('criterespdos', 'nouvelles') ? 'Nouvelles PDOs' : null;

					$selectRecherche['criterespdos_index'] =
							( Configure::read( 'Cg.departement' ) != 66 ) &&
							$this->Permissions->check('criterespdos', 'nouvelles') ? 'Liste des PDOs' : null;

					$selectRecherche['criteresdossierscovs58_index'] =
							( Configure::read( 'Cg.departement' ) == 58 ) &&
							$this->Permissions->check('criteresdossierscovs58', 'index') ? 'Dossiers COV' : null;

					$selectRecherche['sanctionseps58_selectionradies'] =
							( Configure::read( 'Cg.departement' ) == 58 ) &&
							$this->Permissions->check('sanctionseps58', 'selectionradies') ? 'Radiation Pôle Emploi' : null;

					$selectRecherche['sanctionseps58_selectionnoninscrits'] =
							( Configure::read( 'Cg.departement' ) == 58 ) &&
							$this->Permissions->check('sanctionseps58', 'selectionnoninscrits') ? 'Non inscription Pôle Emploi' : null;

					$selectRecherche['criteresbilanparcours66_index'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('criteresbilansparcours66', 'index') ? 'Bilan de parcours' : null;

					$selectRecherche['defautsinsertionseps66_selectionnoninscrits'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('defautsinsertionseps66', 'selectionnoninscrits') ? 'Non inscrit Pôle emploi' : null;

					$selectRecherche['defautsinsertionseps66_selectionradies'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('defautsinsertionseps66', 'selectionradies') ? 'Radié Pôle emploi' : null;

					$selectRecherche['nonorientationsproseps_index'] =
							( Configure::read( 'Cg.departement' ) == 66 ) &&
							$this->Permissions->check('nonorientationsproseps', 'index') ? 'Demande maintien social' : null;

					$selectRecherche['criterestransfertspdvs93_index'] =
							( Configure::read( 'Cg.departement' ) == 93 ) &&
							$this->Permissions->check('criterestransfertspdvs93', 'index') ? 'Allocataires sortants Intra-département' : null;

					$selectRecherche['demenagementshorsdpts_search'] =
							( Configure::read( 'Cg.departement' ) == 93 ) &&
							$this->Permissions->check('demenagementshorsdpts', 'index') ? 'Allocataires sortants Hors département' : null;

					$selectRecherche['fichesprescriptions93_search'] =
							( Configure::read( 'Cg.departement' ) == 93 ) &&
							$this->Permissions->check('fichesprescriptions93', 'search') ? 'Fiches de prescription' : null;

					$selectRecherche['commissionseps_recherche'] =
							$this->Permissions->check('commissionseps', 'recherche') ? 'Commission EP' : null;
					?>
				<form id="magic_search" action="<?php echo $this->Html->url(array("controller" => "Search", "action" => "search")); ?>" method="post">
					<select name="moteur_de_recherche" alt="Moteur de recherche" title="Moteur de recherche">
					<?php
						foreach ( $selectRecherche as $key => $value ){
							if ($value){
								$selected = ( $this->Session->read('engine') == $key ) ? ' selected' : '';
								echo '<option value="' . $key . '"'. $selected .'>' . $value . '</option>';
							}
						}
					?>
					</select>
					<input type="text" name="search" id="search" value="<?php echo ($this->Session->read('search') != null)?$this->Session->read('search'):''; ?>" title="Recherche (vide:), Rechercher par (nom:), par (adresse:), par numéro (caf:), par numéro de (dossier:),par (nir:), par intitulé Commission (ep:), par (adresse:) allocataire" alt="Rechercher par (nom:), par (adresse:), par numéro (caf:), par numéro de (dossier:),par (nir:), par intitulé Commission (ep:), par (adresse:) allocataire" /><input type="submit" value="" alt="Rechercher" title="Rechercher" />
				</form>
				<?php
				}
					if ($this->Session->check( 'Message.flash' ) ) {
						echo $this->Session->flash();
					}
					if ($this->Session->check( 'Message.auth' ) ) {
						echo $this->Session->flash( 'auth' );
					}

					if( isset( $dossierMenu ) ) {
						echo $this->element( 'dossier_menu', array( 'dossierMenu' => $dossierMenu ) );
						echo '<div class="with_treemenu">';
					}
					echo $this->fetch( 'content' );
					if( isset( $dossierMenu ) ) {
						echo '</div><div class="clearer"><hr /></div>';
					}
				?>
			</div>
			<div id="pageFooter"<?php if( Configure::read( 'debug' ) > 0 ) { echo ' style="color: black;"'; }?>>
				webrsa v. <?php echo app_version();?> 2009 - 2014 @ Adullact.
				<?php
					if( Configure::read( 'debug' ) > 0 ) {
						echo '( CG '.Configure::read( 'Cg.departement' );
						echo ', BDD '.ClassRegistry::init( 'User' )->getDataSource()->config['database'];
						echo ', '.$this->Html->link( 'requêtes SQL', '#', array( 'onclick' => '$( "sqldump" ).toggle();return false;', 'id' => 'SqlDumpToggler' ) );
						echo " )\n";
					}
					echo sprintf(
						"Page construite en %s secondes. %s / %s. %s modèles",
						number_format( microtime( true ) - $_SERVER['REQUEST_TIME'] , 2, ',', ' ' ),
						byteSize( memory_get_peak_usage( false ) ),
						byteSize( memory_get_peak_usage( true ) ),
						count( ClassRegistry::mapKeys() )
					);
				?>
				(CakePHP v. <?php echo Configure::version();?>)
			</div>
		</div>
		<?php
			echo $this->fetch( 'scriptBottom' );

			if( Configure::read( 'debug' ) > 0 ) {
				echo $this->Html->tag( 'div', $this->element( 'sql_dump' ), array( 'id' => 'sqldump', 'style' => 'display: none' ) );
			}
		?>
		<?php if( Configure::read( 'debug' ) > 0 ): ?>
		<script type="text/javascript">
			//<![CDATA[
			$( 'SqlDumpToggler' ).innerHTML = getCakeQueriesCount() + ' ' + $( 'SqlDumpToggler' ).innerHTML;
			//]]>
		</script>
		<?php endif; ?>
	</body>
</html>
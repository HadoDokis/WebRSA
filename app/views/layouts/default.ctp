<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>
		<?php echo $xhtml->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<?php
			if( Configure::read( 'debug' ) ) {
				//echo $xhtml->meta('icon');
				echo $xhtml->css( array( 'all.reset' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $xhtml->css( array( 'all.base' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $xhtml->css( array( 'screen.generic' ), 'stylesheet', array( 'media' => 'screen,presentation' ) );
				echo $xhtml->css( array( 'print.generic' ), 'stylesheet', array( 'media' => 'print' ) );
				echo $xhtml->css( array( 'menu' ), 'stylesheet', array( 'media' => 'all' ) );
				echo $xhtml->css( array( 'popup' ), 'stylesheet', array( 'media' => 'all' ) );


				echo $javascript->link( 'prototype.js' );
				echo $javascript->link( 'tooltip.prototype.js' );
				echo $javascript->link( 'webrsa.common.prototype.js' );

				echo $scripts_for_layout;
			}
			else {
				echo $xhtml->css( array( 'webrsa' ), 'stylesheet' );
				echo $javascript->link( 'webrsa.js' );
			}
		?>
		<!-- TODO: à la manière de cake, dans les vues qui en ont besoin -->
		<script type="text/javascript">
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

				var baseUrl = '<?php echo Router::url( '/', true );?>';
				make_treemenus( baseUrl, <?php echo ( Configure::read( 'UI.menu.large' ) ? 'true' : 'false' );?> );
				make_folded_forms();
				mkTooltipTables();

				// External links
				$$('a.external').each( function ( link ) {
					$( link ).onclick = function() {
						window.open( $( link ).href, 'external' ); return false;
					};
				} );

				if ('<?php echo Router::url( "/users/login", true ); ?>' != location.href && '<?php echo Configure::read("alerteFinSession"); ?>') {
					var sessionTime = parseInt('<?php echo ini_get("session.gc_maxlifetime") ?>');
					var warning5minutes = sessionTime - (5*60);
					setTimeout(alert5minutes, warning5minutes*1000);
					setTimeout(sessionEnd, sessionTime*1000);
				}
			});

			function alert5minutes() {
				$('alertEndSession').show();
			}

			function sessionEnd() {
				var baseUrl = '<?php echo Router::url( "/users/logout", true ); ?>';
				location.replace(baseUrl);
			}

		</script>
		<!--[if IE]>
			<style type="text/css" media="screen, presentation">
				.treemenu { position: relative; }
				.treemenu, .treemenu *, #pageMenu, #pageWrapper { zoom: 1; }
			</style>
		<![endif]-->
	</head>
	<?php if( $this->base.'/' == $this->here ): ?>
		<body class="home">
	<?php else: ?>
		<body>
	<?php endif; ?>

<script type="text/javascript">
    function impressionCohorte( link ) {
        $( 'alertEndSession' ).show();
    }
</script>

<!-- Partie nécessaire pour l'affichage du popup lors du lancement des impressions en cohorte -->
<div id="alertEndSession" style="display: none;">
    <div id="popups" style="z-index: 1000;">
        <div id="popup_0">
            <div class="hideshow">
                <div class="fade" style="z-index: 31"></div>
                <div class="popup_block">
                    <div class="popup">
		    	<a href="#" onclick="$('alertEndSession').hide(); return false;"><?php echo $xhtml->image('icon_close.png', array('class' => 'cntrl', 'alt' => 'close')); ?></a>
                        <div id="popup-content">Attention votre session expire dans 5 minutes.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

		<div id="pageWrapper"<?php if( Configure::read( 'UI.menu.large' ) ) { echo ' class="treemenu_large"'; } ?>>
			<div id="pageHeader">
				&nbsp;
			</div>
			<?php
				if( $session->check( 'Auth.User.username' ) ) {
					echo $this->element( 'menu', array( 'cache' => array ( 'time' => '+1 day', 'key' => $session->read( 'Auth.User.username' ) ) ) );
					echo $this->element( 'cartouche' );
				}
			?>
			<div id="pageContent">
				<?php
					if ($session->check( 'Message.flash' ) ) {
						$session->flash();
					}
					if ($session->check( 'Message.auth' ) ) {
						$session->flash( 'auth' );
					}
				?>
				<?php echo $content_for_layout;?>
			</div>
			<div id="pageFooter"<?php if( Configure::read( 'debug' ) > 0 ) { echo ' style="color: black;"'; }?>>
				webrsa v. <?php echo app_version();?> 2009 - 2011 @ Adullact.
				<?php
					echo sprintf(
						"Page construite en %s secondes. %s / %s. %s modèles",
						number_format( getMicrotime() - $GLOBALS['TIME_START'] , 2, ',', ' ' ),
						byteSize( memory_get_peak_usage( false ) ),
						byteSize( memory_get_peak_usage( true ) ),
						class_registry_models_count()
					);
				?>
				(CakePHP v. <?php echo core_version();?>)
			</div>
		</div>
	</body>
</html>

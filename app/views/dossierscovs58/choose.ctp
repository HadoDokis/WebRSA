<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la commission de la COV « %s » du %s',
			$cov58['Cov58']['name'],
			$locale->date( 'Locale->datetime', $cov58['Cov58']['datecommission'] )
		);
		
		echo $form->create('Covs58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	?>
</h1>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="dossierscovs">
		<?php
			foreach( $themes as $theme ) {
				$class = Inflector::classify( $theme );
				echo "<div id=\"$theme\"><h3 class=\"title\">".__d( Inflector::underscore( Inflector::classify( $theme ) ),  'ENUM::THEMECOV::'.$theme, true )."</h3>";
					if (empty($dossierscovs[$class])) {
						echo "Il n'y a aucun dossier en attente pour ce thème";
					}
					else {
						echo "<table><thead><tr>";
							echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.chosen', true ) );
							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.qual', true ) );
							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.prenom', true ) );
							echo $xhtml->tag( 'th', __d( $theme, $class.'.datedemande', true ) );
						echo "</tr></thead><tbody>";
						foreach($dossierscovs[$class] as $key => $dossiercov) {
							echo "<tr>";
								echo $form->input( $class.'.'.$key.'.id', array( 'type' => 'hidden', 'value' => $dossiercov['Dossiercov58']['id'] ) );
								echo $xhtml->tag( 'td', $form->input( $class.'.'.$key.'.chosen', array( 'type' => 'checkbox', 'label' => false, 'checked' => ( $dossiercov['chosen'] == 1 ) ? 'checked' : false ) ) );
								echo $xhtml->tag( 'td', $dossiercov['Personne']['qual'] );
								echo $xhtml->tag( 'td', $dossiercov['Personne']['nom'] );
								echo $xhtml->tag( 'td', $dossiercov['Personne']['prenom'] );
								echo $xhtml->tag( 'td', $dossiercov[$class]['datedemande'] );
							echo "</tr>";
						}
						echo "</tbody></table>";
					}
				echo "</div>";
			}
		?>
	</div>
</div>
<?php
	echo $form->end('Valider');
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
	
    echo $default->button(
        'back',
        array(
            'controller' => 'covs58',
            'action'     => 'view',
            $cov58_id
        ),
        array(
            'id' => 'Back'
        )
    );
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 3 );
</script>
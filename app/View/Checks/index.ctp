<h1><?php echo $this->pageTitle = 'Vérification de l\'application'; ?></h1>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="software">
		<h2 class="title">Environnement logiciel</h2>
		<div id="tabbedWrapperSoftware" class="tabs">
			<div id="apache">
				<h3 class="title">Apache</h3>
				<?php echo $this->Checks->table( $results['Apache']['informations'] );?>
				<h4>Modules</h4>
				<?php echo $this->Checks->table( $results['Apache']['modules'] );?>
			</div>
			<div id="binaries">
				<h3 class="title">Binaires</h3>
				<?php echo $this->Checks->table( $results['Environment']['binaries'] );?>
			</div>
			<div id="cakephp">
				<h3 class="title">CakePHP</h3>
				<?php echo $this->Checks->table( $results['Cakephp']['informations'] );?>
			</div>
			<div id="directories">
				<h3 class="title">Installation</h3>
				<h4>Répertoires</h4>
				<?php echo $this->Checks->table( $results['Environment']['directories'] );?>
				<h4>Fichiers</h4>
				<?php echo $this->Checks->table( $results['Environment']['files'] );?>
				<h4>Cache</h4>
				<?php echo $this->Checks->table( $results['Environment']['cache'] );?>
				<h4>Espace libre</h4>
				<?php echo $this->Checks->table( $results['Environment']['freespace'] );?>
			</div>
			<div id="php">
				<h3 class="title">PHP</h3>
				<?php echo $this->Checks->table( $results['Php']['informations'] );?>
				<h4>Configuration</h4>
				<?php echo $this->Checks->table( $results['Php']['inis'] );?>
				<h4>Extensions</h4>
				<?php echo $this->Checks->table( $results['Php']['extensions'] );?>
				<h4>Extensions PEAR</h4>
				<?php echo $this->Checks->table( $results['Php']['pear_extensions'] );?>
			</div>
			<div id="postgresql">
				<h3 class="title">PostgreSQL</h3>
				<?php echo $this->Checks->table( $results['Postgresql'] );?>
			</div>
			<div id="webrsa">
				<h3 class="title">WebRSA</h3>
				<?php echo $this->Checks->table( $results['Webrsa']['informations'] );?><br/>
				<div id="tabbedWrapperWebrsa" class="tabs">
					<div id="webrsa_configuration">
						<h4 class="title">Configuration</h4>
						<?php echo $this->Checks->table( $results['Webrsa']['configure'] );?>
					</div>
					<div id="webrsa_pgsqlintervals">
						<h4 class="title">Intervalles PostgreSQL</h4>
						<?php echo $this->Checks->table( $results['Webrsa']['intervals'] );?>
					</div>
					<?php if( !is_null( Configure::read( "Recherche.qdFilters" ) ) ):?>
					<div id="webrsa_sqrecherche">
						<h4 class="title">Fragments SQL pour les moteurs de recherche</h4>
						<?php
							foreach( $results['Webrsa']['sqRechercheErrors'] as $modelName => $entries ) {
								$errorClass = ( empty( $entries ) ? '' : 'error' );
								echo "<h5 class=\"title {$errorClass}\">{$modelName}</h5>";
								$controllerName = Inflector::camelize( Inflector::tableize( $modelName ) );
								echo $this->Default2->index(
									$entries,
									array(
										"{$modelName}.id" => array( 'type' => 'integer' ),
										"{$modelName}.name" => array( 'type' => 'string' ),
										"{$modelName}.sqrecherche" => array( 'type' => 'string' ),
									),
									array(
										'actions' => array(
											"{$controllerName}::edit" => array( 'class' => 'external' ),
										)
									)
								);
							}
						?>
					</div>
					<?php endif;?>
					<div id="webrsa_configure_querydata_fragments">
						<h4 class="title">Morceaux de querydata dans le webrsa.inc</h4>
						<?php
							foreach( $results['Webrsa']['querydata_fragments_errors'] as $modelName => $entries ) {
								$errorClass = ( !in_array( false, Hash::extract( $entries, '{s}.success' ), true ) ? '' : 'error' );
								echo "<h5 class=\"title {$errorClass}\">{$modelName}</h5>";
								echo $this->Checks->table( $entries );
							}
						?>
					</div>
					<div id="webrsa_configure_primary_key">
						<h4 class="title">Clés primaires dans le webrsa.inc</h4>
						<?php echo $this->Checks->table( $results['Webrsa']['configure_primary_key'] );?>
					</div>
					<div id="webrsa_configure_regexps">
						<h4 class="title">Expressions rationnelles dans le webrsa.inc</h4>
						<?php echo $this->Checks->table( $results['Webrsa']['configure_regexps'] );?>
					</div>
					<div id="webrsa_configure_fields">
						<h4 class="title">Champs spécifiés dans le webrsa.inc</h4>
						<?php echo $this->Checks->table( $results['Webrsa']['configure_fields'] );?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="modeles">
		<h2 class="title">Modèles de documents</h2>
		<h3>Paramétrables</h3>
		<?php echo $this->Checks->table( $results['Modelesodt']['parametrables'] );?>
		<h3>Statiques</h3>
		<?php echo $this->Checks->table( $results['Modelesodt']['statiques'] );?>
	</div>
	<div id="data">
		<h2 class="title">Données stockées en base</h2>
		<?php foreach( $results['Storeddata']['errors'] as $tablename => $errors ):?>
		<h3 class="storeddata <?php echo ( count( $errors ) > 0 ? 'error' : 'success' );?>"><?php echo h( $tablename );?></h3>
		<?php
			$fields = array();
			$controllerName = Inflector::camelize( $tablename );
			$modelName = Inflector::classify( $controllerName );

			if( count( $errors ) > 0 ) {
				$fields = array_keys( Hash::flatten( $errors[0] ) );
			}

			$cohorteParams = array();
			if( in_array( "{$modelName}.id", $fields ) ) {
				$cohorteParams = array(
					'actions' => array(
						"{$controllerName}::edit"
					)
				);
			}

			echo $this->Default2->index(
				$errors,
				$fields,
				$cohorteParams
			);
		?>
		<?php endforeach;?>
	</div>
	<div id="services">
		<h2 class="title">Services</h2>
		<?php foreach( $results['Services'] as $serviceName => $serviceResults ):?>
			<h3><?php echo h( $serviceName );?></h3>
			<?php if( !empty( $serviceResults['configure'] ) ):?>
				<h4>Configuration</h4>
				<?php echo $this->Checks->table( $serviceResults['configure'] );?>
			<?php endif;?>
			<?php if( !empty( $serviceResults['tests'] ) ):?>
				<h4>Tests</h4>
				<?php echo $this->Checks->table( $serviceResults['tests'] );?>
			<?php endif;?>
		<?php endforeach;?>
	</div>
	<?php if( !empty( $results['Emails'] ) ): ?>
	<div id="emails">
		<h2 class="title">Emails</h2>
		<?php foreach( $results['Emails'] as $emailName => $emailResults ):?>
			<h3><?php echo h( $emailName );?></h3>
			<?php if( !empty( $emailResults['configure'] ) ):?>
				<h4>Configuration</h4>
				<?php echo $this->Checks->table( $emailResults['configure'] );?>
			<?php endif;?>
			<?php if( !empty( $emailResults['tests'] ) ):?>
				<h4>Tests</h4>
				<?php echo $this->Checks->table( $emailResults['tests'] );?>
			<?php endif;?>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>
<script type="text/javascript">
	// On tronque la longueur des titres à 25 caractères avant de faire les onglets.
	$$( 'h2, h3, h4, h5, h6' ).each( function( title ) { truncateWithEllipsis( title, 25 ); } );

	// Création des onglets à partir des titres.
	makeTabbed( 'tabbedWrapper', 2 );
	makeTabbed( 'tabbedWrapperSoftware', 3 );
	makeTabbed( 'tabbedWrapperWebrsa', 4 );
	makeErrorTabs();
</script>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
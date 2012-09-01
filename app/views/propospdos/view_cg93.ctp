<?php
	$this->pageTitle = 'Détails demande PDO';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<?php
	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}
?>

<div class="with_treemenu">
	<h1>Détails demande PDO</h1>
	<ul class="actionMenu">
		<?php
			if( $permissions->check( 'propospdos', 'edit' ) ) {
				echo '<li>'.$xhtml->editLink(
					'Éditer PDO',
					array( 'controller' => 'propospdos', 'action' => 'edit', Set::classicExtract( $pdo, 'Propopdo.id' ) )
				).' </li>';
			}
		?>
	</ul>

	<?php
		echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

		$complet = Set::enum( $pdo['Propopdo']['iscomplet'], $options['iscomplet'] );
		$origpdo = Set::enum( $pdo['Propopdo']['originepdo_id'], $originepdo );
		$motifpdo = Set::enum( $pdo['Propopdo']['motifpdo'], $motifpdo );
		$structure = Set::enum( $pdo['Propopdo']['structurereferente_id'], $structs );
		$decision = Set::enum( $pdo['Decisionpropopdo'][0]['decisionpdo_id'], $decisionpdo );
		echo $default2->view(
			$pdo,
			array(
				'Structurereferente.lib_struc' => array( 'type' => 'text', 'value' => $structure ),
				'Typepdo.libelle',
				'Propopdo.datereceptionpdo',
				'Propopdo.originepdo_id' => array( 'type' => 'text', 'value' => $origpdo ),
				'Decisionpropopdo.decisionpdo_id' => array( 'type' => 'text', 'value' => $decision ),
				'Propopdo.motifpdo' => array( 'type' => 'text', 'value' => $motifpdo ),
				'Decisionpropopdo.0.datedecisionpdo',
				'Decisionpropopdo.0.commentairepdo',
				'Propopdo.iscomplet' => array( 'type' => 'text', 'value' => $complet ),
			),
			array(
				'class' => 'aere'
			)
		);

		echo "<h2>Pièces jointes</h2>";
		echo $fileuploader->results( Set::classicExtract( $pdo, 'Fichiermodule' ) );
	?>

</div>
	<div class="submit"> <?php echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?> </div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>
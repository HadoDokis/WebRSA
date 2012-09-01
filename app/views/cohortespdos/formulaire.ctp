<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

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

	if( isset( $cohortepdo ) ) {
		$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>

<?php  require_once( 'filtre.ctp' );?>
<!-- Résultats -->

<?php if( isset( $cohortepdo ) ):?>
	<?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
		<?php echo $pagination;?>
		<?php echo $form->create( 'Cohortepdo', array( 'url'=> Router::url( null, true ) ) );?>
		<?php
			echo '<div>';
			echo $form->input( 'Cohortepdo.numcomptt', array( 'type' => 'hidden', 'id' => 'CohortepdoNumcomptt2' ) );
			echo $form->input( 'Cohortepdo.matricule', array( 'type' => 'hidden', 'id' => 'CohortepdoMatricule2' ) );
			echo $form->input( 'Cohortepdo.nom', array( 'type' => 'hidden', 'id' => 'CohortepdoNom2' ) );
			echo $form->input( 'Cohortepdo.prenom', array( 'type' => 'hidden', 'id' => 'CohortepdoPrenom2' ) );
			echo $form->input( 'Cohortepdo.locaadr', array( 'type' => 'hidden', 'id' => 'CohortepdoLocaadr2' ) );
			echo $form->input( 'Cohortepdo.user_id', array( 'type' => 'hidden', 'id' => 'CohortepdoUserId2' ) );
			echo '</div>';
		?>

		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th>Nom de l'allocataire</th>
					<th>Date de demande RSA</th>
					<th>Adresse</th>
					<th>Gestionnaire</th>
					<th>Commentaires</th>
					<th class="action noprint">Action</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohortepdo as $index => $pdo ):?>
				<?php
					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>N° Dossier</th>
									<td>'.h( $pdo['Dossier']['numdemrsa'] ).'</td>
								</tr>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $pdo['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Numéro CAF</th>
									<td>'.h( $pdo['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $pdo['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $pdo['Adresse']['codepos'] ).'</td>
								</tr>
								<tr>
									<th>État du dossier</th>
									<td>'.h( $etatdosrsa[$pdo['Situationdossierrsa']['etatdosrsa']] ).'</td>
								</tr>
							</tbody>
						</table>';
						$title = $pdo['Dossier']['numdemrsa'];


					$personne_id = $pdo['Personne']['id'];

					echo $xhtml->tableCells(
						array(
							h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
							h( date_short( $pdo['Dossier']['dtdemrsa'] ) ),
							h( Set::classicExtract( $pdo, 'Adresse.locaadr' ) ),

							$form->input( 'Propopdo.'.$index.'.personne_id', array( 'label' => false, 'div' => false, 'value' => $personne_id, 'type' => 'hidden' ) ).
							$form->input( 'Propopdo.'.$index.'.id', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) ).

							$form->input( 'Propopdo.'.$index.'.user_id', array('label' => false, 'type' => 'select', 'options' => $gestionnaire, 'empty' => true ) ),

							$form->input( 'Propopdo.'.$index.'.commentairepdo', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
							$xhtml->viewLink(
								'Voir le dossier « '.$pdo['Dossier']['numdemrsa'].' »',
								array( 'controller' => 'suivisinsertion', 'action' => 'index', $pdo['Dossier']['id'] ),
								true,
								true
							),
							array( $innerTable, array( 'class' => 'innerTableCell' ) )
						),
						array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
						array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
					);
				?>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php echo $pagination;?>
		<?php echo $form->submit( 'Validation de la liste' );?>
		<?php echo $form->end();?>

	<?php else:?>
		<p>Aucune PDO dans la cohorte.</p>
	<?php endif?>
<?php endif?>
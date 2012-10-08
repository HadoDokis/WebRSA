<?php
	$this->pageTitle =  __d( 'personnepcg66', "Personnespcgs66::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyerId ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Personnepcg66', array( 'type' => 'post', 'id' => 'personnepcg66form', 'url' => Router::url( null, true ) ) );

		//Liste des différentes situations de la personne
		$listeSituations = Set::extract( $personnepcg66, '/Situationpdo/libelle' );
		$differentesSituations = '';
		foreach( $listeSituations as $key => $situation ) {
			if( !empty( $situation ) ) {
				$differentesSituations .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$situation.'</li></ul>';
			}
		}

		//Liste des différents statuts de la personne
		$listeStatuts = Set::extract( $personnepcg66, '/Statutpdo/libelle' );
		$differentsStatuts = '';
		foreach( $listeStatuts as $key => $statut ) {
			if( !empty( $statut ) ) {
				$differentsStatuts .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
			}
		}
		echo $default2->view(
			$personnepcg66,
			array(
				'Personne.nom_complet' => array( 'type' => 'string', 'value' => '#Personne.qual# #Personne.nom# #Personne.prenom#' ),
				'Situationpdo.libelle' => array( 'value' => $differentesSituations ),
				'Statutpdo.libelle' => array( 'value' => $differentsStatuts ),
			),
			array(
				'class' => 'aere'
			)
		);
	?>
</div>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>
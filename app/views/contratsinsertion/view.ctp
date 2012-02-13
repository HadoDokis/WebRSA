<?php
	$this->pageTitle =  __d( 'contratinsertion', "Contratsinsertion::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Contratsinsertionview', array( 'type' => 'post', 'id' => 'contratform', 'url' => Router::url( null, true ) ) );

		$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
		if ( isset( $contratinsertion['Contratinsertion']['avenant_id'] ) && !empty( $contratinsertion['Contratinsertion']['avenant_id'] ) ) {
			$num = 'Avenant';
		}
		else{
			$num = Set::enum( $contratinsertion['Contratinsertion']['num_contrat'], $options['num_contrat'] );
		}
		$duree = Set::enum( $contratinsertion['Contratinsertion']['duree_engag'], $$duree_engag );
		$forme = Set::enum( $contratinsertion['Contratinsertion']['forme_ci'], $forme_ci );
		$decision_ci = Set::enum( $contratinsertion['Contratinsertion']['decision_ci'], $decision_ci );

		if( Configure::read( 'Cg.departement' ) == 58 ) {
			echo $default2->view(
				$contratinsertion,
				array(
					'Personne.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.num_contrat' => array( 'type' => 'text', 'value' => $num ),
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.rg_ci' => array( 'type' => 'text' ),
					'Contratinsertion.duree_engag' => array( 'type' => 'text', 'value' => $duree ),
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci'
				),
				array( 'id' => 'vueContrat' )
			);
		}
		else if( Configure::read( 'Cg.departement' ) == 66 ) {

			if( ( $contratinsertion['Contratinsertion']['positioncer'] == 'annule' )  && empty( $contratinsertion['Contratinsertion']['decision_ci'] ) ){
			
				echo $html->tag('div', $html->tag('strong', 'Raison de l\'annulation'));
				echo $default->view(
					$contratinsertion,
					array(
						'Contratinsertion.motifannulation' => array( 'type' => 'text' )
					),
					array(
						'widget' => 'table',
						'class' => 'aere'
					)
				);

			}
			else if( ( $contratinsertion['Contratinsertion']['positioncer'] == 'annule' ) && ( $contratinsertion['Contratinsertion']['decision_ci'] == 'N' ) ){
			
				echo $html->tag('div', $html->tag('strong', 'Raison de l\'annulation'));
				echo $default->view(
					$contratinsertion,
					array(
						'Contratinsertion.observ_ci' => array( 'type' => 'text' )
					),
					array(
						'widget' => 'table',
						'class' => 'aere'
					)
				);
			}
		
			echo $default2->view(
				$contratinsertion,
				array(
					'Personne.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.forme_ci' => array( 'type' => 'text', 'value' => $forme  ),
					'Contratinsertion.num_contrat' => array( 'type' => 'text', 'value' => $num ),
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.rg_ci' => array( 'type' => 'text' ),
					'Contratinsertion.sitfam_ci',
					'Contratinsertion.sitpro_ci',
					'Contratinsertion.observ_benef',
					'Contratinsertion.nature_projet',
					'Contratinsertion.engag_object',
					'Contratinsertion.duree_engag' => array( 'type' => 'text', 'value' => $duree ),
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.lieu_saisi_ci' => array( 'type' => 'text' ),
					'Contratinsertion.date_saisi_ci',
					'Contratinsertion.decision_ci' => array( 'type' => 'text', 'value' => $decision_ci ),
					'Contratinsertion.datedecision' => array( 'type' => 'date' ),
				),
				array( 'class' => 'aere', 'id' => 'vueContrat' )
			);
			echo '<h2 >Actions déjà en cours</h2>';
			if( !empty( $fichescandidature ) ) {
				foreach( $fichescandidature as $fichecandidature ){
					echo $default2->view(
						$fichecandidature,
						array(
							'Actioncandidat.name' => array( 'type' => 'text' ),
							'Actioncandidat.Contactpartenaire.Partenaire.libstruc' => array( 'type' => 'text' ),
							'Referent.nom' => array( 'type' => 'text', 'value' => '#Referent.qual# #Referent.nom# #Referent.prenom#' ),
							'Actioncandidat.ddaction',
							'Actioncandidat.hasfichecandidature' => array( 'type' => 'boolean' )
						)
					);
				}
			}
			else{
				echo '<p class="notice">Aucune action en cours</p>';
			}
		}
		else if( Configure::read( 'Cg.departement' ) == 93 ) {
			echo $default2->view(
				$contratinsertion,
				array(
					'Personne.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.forme_ci' => array( 'type' => 'text', 'value' => $forme  ),
					'Contratinsertion.num_contrat' => array( 'type' => 'text', 'value' => $num ),
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.nom_complet' => array( 'type' => 'text' ),
					'Contratinsertion.rg_ci' => array( 'type' => 'text' ),
					'Contratinsertion.diplomes',
					'Contratinsertion.expr_prof',
					'Contratinsertion.form_compl',
					'Contratinsertion.actions_prev' => array( 'type' => 'boolean' ),
					'Contratinsertion.obsta_renc',
					'Contratinsertion.nature_projet',
					'Action.libelle' => array( 'label' => 'Action engagée' ),
					'Actioninsertion.dd_action' => array( 'label' => 'Date de début de l\'action' ),
					'Contratinsertion.duree_engag' => array( 'type' => 'text', 'value' => $duree ),
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.lieu_saisi_ci' => array( 'type' => 'text' ),
					'Contratinsertion.date_saisi_ci',
				)/*,
				array( 'id' => 'vueContrat' )*/
			);
		}
// debug($contratinsertion);
	?>
	<div class="submit">
		<?php echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?>
	</div>
	<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>
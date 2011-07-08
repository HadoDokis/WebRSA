<fieldset>
	<legend class="title" title="Exemples: logement, santé, disponibilité, autonomie, ...">Situation personnelle et familiale </legend>
	<?php echo $form->input( 'Contratinsertion.sitfam_ci', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Exemples: qualification, connaissances et compétences, formation recherchée, nature de l'emploi ou des emplois recherchés, ...">Situation professionnelle </legend>
	<?php echo $form->input( 'Contratinsertion.sitpro_ci', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Exemples: ce que j'attends, ce que je propose">Observation(s) éventuelle(s) du bénéficiaire du contrat</legend>
	<?php echo $form->input( 'Contratinsertion.observ_benef', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Projets et démarches que le bénéficiaire du contrat s'engage à entreprendre au regard de la proposition du référent">Projet négocié <?php echo REQUIRED_MARK;?></legend>
	<?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>

<fieldset>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<fieldset>
					<legend><strong>Positionnement éventuel sur l'action d'insertion</strong></legend>
					<?php echo $form->input( 'Contratinsertion.engag_object', array( 'label' => 'Intitulé de ( ou des ) actions', 'type' => 'textarea' ) );?>
					<?php
						//FIXME
						$contratinsertion_id = Set::extract( $this->data, 'Actioninsertion.contratinsertion_id' );
						if( $this->action == 'edit' && !empty( $contratinsertion_id ) ) :?>
						<?php echo $form->input( 'Actioninsertion.contratinsertion_id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
					<?php endif;?>
					<?php
//                         echo $default->subform(
//                             'Contratinsertion.actioncandi'
//                         );
					?>


				</fieldset>

			</td>
			<td class="mediumSize noborder">
				<fieldset>
					<legend> <strong>Action(s) déjà en cours</strong></legend>
						<?php if( !empty( $fichescandidature ) ):?>
						<table>
							<thead>
								<tr>
									<th>Action engagée</th>
									<th>Partenaire / Prestataire</th>
									<th>Prescripteur</th>
									<th>Date de début de l'action</th>
									<th>Fiche de candidature ?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach( $fichescandidature as $key => $fiche )
									{
										echo '<tr>';
											echo $xhtml->tag('td', $fiche['Actioncandidat']['name']);
											echo $xhtml->tag('td', $fiche['Actioncandidat']['Contactpartenaire']['Partenaire']['libstruc'] );
											echo $xhtml->tag('td', $fiche['Referent']['qual'].' '.$fiche['Referent']['nom'].' '.$fiche['Referent']['prenom'] );
											echo $xhtml->tag('td', date_short( $fiche['Actioncandidat']['ddaction'] ) );
											echo $xhtml->tag('td', $fiche['Actioncandidat']['hasfichecandidature'] ? 'Oui' : 'Non' );
											echo $xhtml->tag('td', $xhtml->viewLink( 'Voir', array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $fiche['ActioncandidatPersonne']['personne_id'] ) ) );
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
						<?php else:?>
							<p class="notice">Aucune action engagée pour cet allocataire.</p>
						<?php endif;?>
				</fieldset>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<p>
		Entre <?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?> bénéficiare du rSa et le Département représenté par le référent signataire désigné par l'organisme choisi par le Président du Conseil Général, il est conclu le présent contrat visant à faciliter son insertion sociale ou professionnelle.<br />
		Le bénéficiaire <strong>s'engage à respecter les orientations et le suivi</strong> du parcours d'insertion, ainsi que les différents moyens d'actions proposés. Le Département, représenté par le référent signataire désigné par l'organisme choisi par le Président du Conseil Général <strong>s'engage à mettre en oeuvre les actions pré-citées et/ou un accompagnement adapté.</strong>
	</p>
</fieldset>
<fieldset>
	<table class="wide noborder">
		<tr>
			<td colspan="2" class="noborder center">
				<em>Le présent contrat est conclu pour une durée de <?php echo REQUIRED_MARK;?></em>
				<?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => false, 'div' => false, 'type' => 'select', 'options' => $duree_engag_cg66, 'empty' => true )  ); ?>
			</td>
		</tr>
		<tr>
			<td class="mediumSize noborder">
				<strong>Du <?php echo REQUIRED_MARK;?></strong><?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => false, 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 , 'empty' => true)  );?>
			</td>
			<td class="mediumSize noborder">
				<strong>Au <?php echo REQUIRED_MARK;?></strong><?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => false, 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 , 'empty' => true ) ) ;?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<table class="wide noborder">
		<tr>
			<td class="signature noborder center">
				<strong>Le bénéficiaire du contrat</strong><br /><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?>
			</td>
			<td class="signature noborder center">
				<strong>Le Référent</strong><br />
				<?php
					echo $xhtml->tag(
						'div',
						$xhtml->tag( 'span', ( isset( $ReferentNom ) ? $ReferentNom : ' ' ), array( 'id' => 'ReferentNom' ) )
					);
					echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentNom', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) );
				?>
			</td>
		</tr>
		<tr>
			<td class="mediumSize noborder"></td>
			<td class="mediumSize noborder">
				<p class="caution center">Attention : lorsque le contrat conditionne le paiement du rsa il ne sera effectif qu'après décision du Président du Conseil Général. La responsabilité du référent signataire n'est nullement engagée par la signature de ce contrat</p>
			</td>
		</tr>
	</table>
	<br />
		<?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.lieu_saisi_ci', true ).REQUIRED_MARK, 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
		<?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true )  ); ?>

</fieldset>
<script type="text/javascript">
	Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiDay' ).value = $F( 'ContratinsertionDdCiDay' );
	} );
	Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiMonth' ).value = $F( 'ContratinsertionDdCiMonth' );
	} );
	Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiYear' ).value = $F( 'ContratinsertionDdCiYear' );
	} );
</script>

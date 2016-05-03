<?php require_once( dirname( __FILE__ ).DS.'search.ctp' ); ?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>2 - Nature des actions d'insertion inscrites dans les contrats d'engagement réciproque en cours de validité</h2>
	<?php
		$annee = Hash::get( $this->request->data, 'Search.annee' );
	?>
	<table>
		<caption>2 - Nature des actions d'insertion inscrites dans les contrats d'engagement réciproque en cours de validité au cours de l'année <?php echo $annee;?></caption>
		<tbody>
			<tr>
				<th><strong>a. Actions des contrats d'engagement réciproque en matière d'insertion professionnelle en cours de validité au 31 décembre (1)</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurnature.delai_moyen_orientation' ) );?></strong></td>
			</tr>
			<?php foreach( $results['Indicateurnature']['spe'] as $label => $count ):?>
				<tr>
					<th><?php echo $label;?></th>
					<td><?php
						if( $count !== null ) {
							echo $this->Locale->number( $count );
						}
						else {
							echo 'ND';
						}
					?></td>
				</tr>
			<?php endforeach;?>
			<tr>
				<th><strong>b. Actions des contrats d'engagement réciproque en matière d'insertion sociale ou professionnelle en cours de validité au 31 décembre (2)</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurnature.delai_moyen_orientation' ) );?></strong></td>
			</tr>
			<?php foreach( $results['Indicateurnature']['horsspe'] as $label => $count ):?>
				<tr>
					<th><?php echo $label;?></th>
					<td><?php
						if( $count !== null ) {
							echo $this->Locale->number( $count );
						}
						else {
							echo 'ND';
						}
					?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<p>(1) Selon la loi, un <strong>Contrat d’Engagement Réciproque en matière d’insertion professionnelle</strong> (L262-35) est signé par la personne bénéficiaire du RSA orientée vers un <strong>organisme participant au service public de l’emploi (SPE) autre que Pôle emploi</strong> : autres organismes publics de placement professionnel (PLIE, AFPA, maison de l’emploi, mission locale, etc.), organismes d’appui à la création et au développement d’entreprise, entreprises de travail temporaire, agences privées de placement, insertion par l’activité économique (IAE), autres organismes publics ou privés de placement professionnel.. Le <strong>SPE</strong> est compris au sens large comme l’<strong>ensemble des organismes compétents en matière d’insertion professionnelle</strong>.</p>
	<p>(2) Selon la loi, un <strong>Contrat d’Engagement Réciproque en matière d’insertion sociale ou professionnelle</strong> (L262-36) est signé par la personne bénéficiaire du RSA orientée vers un <strong>autre organisme</strong>: Conseil général, Caf, Msa, CCAS/CIAS, associations d’insertion, autres organismes d’insertion, Agence départementale d’insertion dans les DOM.</p>
	<p>Un contrat ayant <u>plusieurs actions inscrites</u> sera comptabilisé autant de fois qu’il y a d’actions.</p>
<?php endif;?>
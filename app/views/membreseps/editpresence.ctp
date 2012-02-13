<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Présence des membres à la commission d\'EP du : '.$locale->date( "Datetime::short", $commissionep['Commissionep']['dateseance']);?>

	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if ( $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) {
			echo "<p class='error'>Quorum non atteint, la commission ne peut avoir lieu.</p>";
			if ( isset( $messageQuorum ) && !empty( $messageQuorum ) ) {
				echo "<p class='error'>{$messageQuorum}</p>";
			}
		}
	?>

	<?php echo $xform->create( 'Membreep', array( 'type' => 'post', 'url' => '/membreseps/editpresence/'.$seance_id ) ); ?>
		<div class="aere">
			<fieldset>
				<legend>Liste des participants</legend>
				<?php
					echo "<table id='listeParticipants'>";
					foreach($fonctionsmembres as $fonction) {
						echo $html->tag(
							'tr',
							$html->tag(
								'td',
								$fonction['Fonctionmembreep']['name'].' :',
								array(
									'colspan' => 3
								)
							)
						);
						foreach( $membres as $membre ) {

							if ( $membre['Membreep']['fonctionmembreep_id'] == $fonction['Fonctionmembreep']['id'] ) {
								if ( empty( $membre['CommissionepMembreep']['presence'] ) ) {
									if ( $membre['CommissionepMembreep']['reponse'] == 'confirme' )
										$defaut = 'present';
									elseif ( $membre['CommissionepMembreep']['reponse'] == 'remplacepar' )
										$defaut = 'remplacepar';
									else
										$defaut = 'excuse';
								}
								else {
									$defaut = $membre['CommissionepMembreep']['presence'];
								}

								if ( !empty( $membre['CommissionepMembreep']['presencesuppleant_id'] ) ) {
									$valueDefaut = $membre['CommissionepMembreep']['presencesuppleant_id'];
								}
								elseif ( !empty( $membre['CommissionepMembreep']['reponsesuppleant_id'] ) ) {
									$valueDefaut = $membre['CommissionepMembreep']['reponsesuppleant_id'];
								}
								else {
									$valueDefaut = null;
								}

								echo $html->tag(
									'tr',
									$html->tag(
										'td',
										implode( ' ', array( $membre['Membreep']['qual'], $membre['Membreep']['nom'], $membre['Membreep']['prenom'] ) )
									).
									$html->tag(
										'td',
										$form->input(
											'CommissionepMembreep.'.$membre['Membreep']['id'].'.presence',
											array(
												'type' => 'select',
												'label' => false,
												'default' => $defaut,
												'options' => $options['CommissionepMembreep']['presence'],
												'class' => 'presence'
											)
										),
										array(
											'colspan' => 1
										)
									).
									$html->tag(
										'td',
										$form->input(
											'CommissionepMembreep.'.$membre['Membreep']['id'].'.presencesuppleant_id',
											array(
												'label' => false,
												'type' => 'select',
												'options' => @$membres_fonction[$membre['Membreep']['fonctionmembreep_id']]
											)
										)
									)
								);
							}
						}
					}
					echo "</table>";
				?>
			</fieldset>
		</div>

	<?php echo $xform->end( 'Enregistrer' );?>

	<?php
		if ( Configure::read( 'Cg.departement' ) == 93 ) {
			echo $default->button(
				'back',
				array(
					'controller' => 'commissionseps',
					'action'     => 'arbitrageep'
				),
				array(
					'id' => 'Back'
				)
			);
		}
		else {
			echo $default->button(
				'back',
				array(
					'controller' => 'commissionseps',
					'action'     => 'view',
					$seance_id
				),
				array(
					'id' => 'Back'
				)
			);
		}
	?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		$$('table#listeParticipants select.presence').each(function(select) {
			$(select).observe('change', function() {
				checkPresence(select);
			} );
			checkPresence(select);
		} );
	});

	function checkPresence(select) {
		if (select.getValue() == 'remplacepar') {
			select.up('td').writeAttribute('colspan', 1);
			select.up('td').next().show();
		}
		else {
			select.up('td').writeAttribute('colspan', 2);
			select.up('td').next().hide();
		}
	}
</script>

<div class="clearer"><hr /></div>
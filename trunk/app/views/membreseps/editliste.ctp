<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout de participants à la commission d\'EP';?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout participants';
	}
	else {
		$this->pageTitle = 'Édition participants';
	}
?>
	<h1><?php echo $this->pageTitle;?></h1>
	<?php echo $xform->create( 'Membreep', array( 'type' => 'post', 'url' => '/membreseps/editliste/'.$seance_id ) ); ?>
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
								echo $html->tag(
									'tr',
									$html->tag(
										'td',
										implode(' ', array($membre['Membreep']['qual'], $membre['Membreep']['nom'], $membre['Membreep']['prenom']))
									).
									$html->tag(
										'td',
										$form->input(
											'CommissionepMembreep.'.$membre['Membreep']['id'].'.reponse',
											array(
												'type' => 'select',
												'label' => false,
												'default' => 'nonrenseigne',
												'options' => $options['CommissionepMembreep']['reponse']
											)
										),
										array(
											'id' => 'reponse_membre_'.$membre['Membreep']['id']
										)
									).
									$html->tag(
										'td',
										$form->input( 'CommissionepMembreep.'.$membre['Membreep']['id'].'.reponsesuppleant_id', array( 'label' => false, 'type' => 'select', 'options' => @$membres_fonction[$membre['Membreep']['fonctionmembreep_id']] ) )
// 				        				implode(' ', array($membre['Suppleant']['qual'], $membre['Suppleant']['nom'], $membre['Suppleant']['prenom']))
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
	?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php foreach( $membres as $membre ) { ?>
			$( 'CommissionepMembreep<?php echo $membre['Membreep']['id'] ?>Reponse' ).observe( 'change', function() {
				checkPresence( <?php echo $membre['Membreep']['id'] ?> );
			} );
			checkPresence( <?php echo $membre['Membreep']['id'] ?> );
		<?php } ?>
	} );
	
	function checkPresence( id ) {
		if ( $( 'CommissionepMembreep'+id+'Reponse' ).getValue() == 'remplacepar' ) {
			$( 'reponse_membre_'+id ).writeAttribute('colspan', 1);
			$( 'CommissionepMembreep'+id+'ReponsesuppleantId' ).writeAttribute( 'disabled', false );
			$( 'CommissionepMembreep'+id+'ReponsesuppleantId' ).up('td').show();
		}
		else {
			$( 'reponse_membre_'+id ).writeAttribute('colspan', 2);
			$( 'CommissionepMembreep'+id+'ReponsesuppleantId' ).writeAttribute( 'disabled', 'disabled' );
			$( 'CommissionepMembreep'+id+'ReponsesuppleantId' ).up('td').hide();
		}
	}
</script>

<div class="clearer"><hr /></div>

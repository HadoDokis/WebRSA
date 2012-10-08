<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout participants';
	}
	else {
		$this->pageTitle = 'Édition participants';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>
<?php echo $this->Xform->create( 'Membreep', array( 'type' => 'post', 'url' => '/membreseps/editliste/'.$seance_id ) ); ?>
	<div class="aere">
		<fieldset>
			<legend>Liste des participants</legend>
			<?php
				echo "<table id='listeParticipants'>";
				foreach($fonctionsmembres as $fonction) {
					echo $this->Xhtml->tag(
						'tr',
						$this->Xhtml->tag(
							'td',
							$fonction['Fonctionmembreep']['name'].' :',
							array(
								'colspan' => 3
							)
						)
					);

					foreach( $membres as $membre ) {
						if ( $membre['Membreep']['fonctionmembreep_id'] == $fonction['Fonctionmembreep']['id'] ) {
							echo $this->Xhtml->tag(
								'tr',
								$this->Xhtml->tag(
									'td',
									implode(' ', array($membre['Membreep']['qual'], $membre['Membreep']['nom'], $membre['Membreep']['prenom']))
								).
								$this->Xhtml->tag(
									'td',
									$this->Form->input(
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
								$this->Xhtml->tag(
									'td',
									$this->Form->input( 'CommissionepMembreep.'.$membre['Membreep']['id'].'.reponsesuppleant_id', array( 'label' => false, 'type' => 'select', 'options' => @$membres_fonction[$membre['Membreep']['fonctionmembreep_id']] ) )
								)
							);
						}
					}
				}
				echo "</table>";
			?>
		</fieldset>
	</div>

<?php echo $this->Xform->end( 'Enregistrer' );?>

<?php
	echo $this->Default->button(
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
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
    
    <?php echo $xform->create( 'Membreep', array( 'type' => 'post', 'url' => '/membreseps/editliste/'.$ep_id.'/'.$seance_id ) ); ?>
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
                		foreach($membres as $membre) {
                			if ($membre['Membreep']['fonctionmembreep_id']==$fonction['Fonctionmembreep']['id']) {
				        		echo $html->tag(
				        			'tr',
				        			$html->tag(
				        				'td',
				        				implode(' ', array($membre['Membreep']['qual'], $membre['Membreep']['nom'], $membre['Membreep']['prenom']))
				        			).
				        			$html->tag(
				        				'td',
				        				$form->input(
				        					'CommissionepMembreep.Membreep_id.'.$membre['Membreep']['id'].'.reponse',
				        					array(
				        						'type'=>'select',
				        						'label'=>false,
				        						'default'=>'nonrenseigne',
				        						'options'=>$options['CommissionepMembreep']['reponse'],
				        						'value' => $membre['CommissionepMembreep']['reponse']
				        					)
				        				),
				        				array(
											'colspan' => 1
				        				)
				        			).
				        			$html->tag(
				        				'td',
				        				implode(' ', array($membre['Suppleant']['qual'], $membre['Suppleant']['nom'], $membre['Suppleant']['prenom'])),
				        				array(
				        					'id' => 'suppleant_'.$membre['Membreep']['id']
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
		$$('table#listeParticipants select').each(function(select) {
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

<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout de participants à la séance d\'EP';?>

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
                	echo "<table>";
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
				        					'MembreepSeanceep.Membreep_id.'.$membre['Membreep']['id'].'.reponse',
				        					array(
				        						'type'=>'select',
				        						'label'=>false,
				        						'default'=>'nonrenseigne',
				        						'options'=>$options['MembreepSeanceep']['reponse'],
				        						'value' => $membre['MembreepSeanceep']['reponse']
				        					)
				        				)
				        			).
				        			$html->tag(
				        				'td',
				        				'',
				        				array(
				        					'id' => 'withSuppleant'
				        				)
				        			)/*.
				        			$html->tag(
				        				'td',
				        				'',
				        				array(
				        					'id' => 'withoutSuppleant'
				        				)
				        			)*/
				        		);
                			}
                		}
                	}
                	echo "</table>";
                ?>
            </fieldset>
        </div>

    <?php echo $xform->end( 'Enregistrer' );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //$('MembreepSeanceepMembreep2Reponse')
    });
</script>

<div class="clearer"><hr /></div>

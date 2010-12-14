<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout des participants à une séance d\'EP';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un participant à une séance d\'EP';
	}
?>
</h1>
<div  id="ficheCI">
<table>
	<tbody>
		<tr class="even">
			<th><?php echo "Nom de l'EP";?></th>
			<td><?php echo isset( $options['Ep']['name'] ) ? $options['Ep']['name'] : null ;?></td>
		</tr>
		<tr class="odd">
			<th><?php echo "Regroupement";?></th>
			<td><?php echo isset( $options['Ep']['Regroupementep']['name'] ) ? $options['Ep']['Regroupementep']['name'] : null ;?></td>
		</tr>
		<tr class="even">
			<th><?php echo "Structure référente";?></th>
			<td><?php echo isset( $options['Structurereferente']['lib_struc'] ) ? $options['Structurereferente']['lib_struc'] : null ;?></td>
		</tr>			
		<tr class="odd">
			<th><?php echo "Date de la séance";?></th>
			<td><?php echo isset( $options['Seanceep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $options['Seanceep']['dateseance'])) : null ;?></td>
		</tr>
		<tr class="even">
			<th><?php echo "Décision finale";?></th>
			<td><?php echo isset( $options['Seanceep']['finalisee'] ) ? $options['Seanceep']['finalisee'] : null ;?></td>
		</tr>
	</tbody>
</table>
</div>
<h2>&nbsp;</h2>
<fieldset>
<?php
	echo $default->form(
		array(
			'MembreepSeanceep.seanceep_id' => array('type'=>'hidden', 'value'=>$options['Seanceep']['id']),
			'MembreepSeanceep.membreep_id'=> array('type'=>'select'),
			'MembreepSeanceep.reponse',
			'MembreepSeanceep.presence',		
			'MembreepSeanceep.suppleant'=> array('empty' => false),
			'MembreepSeanceep.suppleant_id'=> array('type'=>'select'),
		),
		array(
			'options' => $options
		)
	);
?>
</fieldset>
<?php 
    echo $default->button(
        'back',
        array(
            'controller' => 'membreseps_seanceseps',
            'action'     => 'index',
        	$options['Seanceep']['id']
        ),
        array(
            'id' => 'Back'
        )
    );

?>
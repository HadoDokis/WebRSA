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
			<td><?php echo isset( $options['Commissionep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $options['Commissionep']['dateseance'])) : null ;?></td>
		</tr>
		<tr class="even">
			<th><?php echo "Décision finale";?></th>
			<td><?php echo isset( $options['Commissionep']['finalisee'] ) ? $options['Commissionep']['finalisee'] : null ;?></td>
		</tr>
	</tbody>
</table>
</div>
<h2>&nbsp;</h2>
<fieldset>
<?php
debug($this->data);
?>
</fieldset>
<?php 
    echo $default->button(
        'back',
        array(
            'controller' => 'commissionseps_membreseps',
            'action'     => 'index',
        	$options['Commissionep']['id']
        ),
        array(
            'id' => 'Back'
        )
    );

?>
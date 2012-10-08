<div class="titressejour index">
<h2><?php __('Titressejour');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('personne_id');?></th>
	<th><?php echo $paginator->sort('dtentfra');?></th>
	<th><?php echo $paginator->sort('nattitsej');?></th>
	<th><?php echo $paginator->sort('menttitsej');?></th>
	<th><?php echo $paginator->sort('ddtitsej');?></th>
	<th><?php echo $paginator->sort('dftitsej');?></th>
	<th><?php echo $paginator->sort('numtitsej');?></th>
	<th><?php echo $paginator->sort('numduptitsej');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($titressejour as $titresejour):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $titresejour['Titresejour']['id']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['personne_id']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['dtentfra']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['nattitsej']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['menttitsej']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['ddtitsej']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['dftitsej']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['numtitsej']; ?>
		</td>
		<td>
			<?php echo $titresejour['Titresejour']['numduptitsej']; ?>
		</td>
		<td class="actions">
			<?php echo $xhtml->link(__('View', true), array('action' => 'view', $titresejour['Titresejour']['id'])); ?>
			<?php echo $xhtml->link(__('Edit', true), array('action' => 'edit', $titresejour['Titresejour']['id'])); ?>
			<?php echo $xhtml->link(__('Delete', true), array('action' => 'delete', $titresejour['Titresejour']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $titresejour['Titresejour']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $xhtml->link(__('New Titresejour', true), array('action' => 'add')); ?></li>
	</ul>
</div>

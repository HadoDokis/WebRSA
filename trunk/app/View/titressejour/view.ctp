<div class="titressejour view">
<h2><?php  __('Titresejour');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Personne Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['personne_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Dtentfra'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['dtentfra']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nattitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['nattitsej']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Menttitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['menttitsej']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ddtitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['ddtitsej']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Dftitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['dftitsej']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Numtitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['numtitsej']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Numduptitsej'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $titresejour['Titresejour']['numduptitsej']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $xhtml->link(__('Edit Titresejour', true), array('action' => 'edit', $titresejour['Titresejour']['id'])); ?> </li>
		<li><?php echo $xhtml->link(__('Delete Titresejour', true), array('action' => 'delete', $titresejour['Titresejour']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $titresejour['Titresejour']['id'])); ?> </li>
		<li><?php echo $xhtml->link(__('List Titressejour', true), array('action' => 'index')); ?> </li>
		<li><?php echo $xhtml->link(__('New Titresejour', true), array('action' => 'add')); ?> </li>
	</ul>
</div>

<?php
	echo $xform->input( 'Membreep.suppleant_id', array( 'label' => __d( 'membreep', 'Membreep.suppleant_id', true ), 'type'=>'select', 'options'=>$listeSuppleant, 'default'=>$defaultvalue, 'empty'=>true, 'div'  => 'updateMe' ) );
?>

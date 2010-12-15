<?php
    if( !empty( $values ) ) {
    	echo '<div class="input"><label>Régime de l\'allocataire</label>';
    	echo $values['Statutpdo'][0]['libelle'];
    	for ($i=1;$i<count($values['Statutpdo']);$i++) {
    		echo '<label></label>'.$values['Statutpdo'][$i]['libelle'];
    	}
        echo '</div>';
    }
?>

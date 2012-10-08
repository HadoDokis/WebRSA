<?php
    $domain = 'modecontact';
    echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id) );
?>

<div class="with_treemenu">

    <?php

        echo $this->Xhtml->tag(
            'h1',
            $this->pageTitle = __d( $domain, "Modescontact::{$this->action}" )
        );
    ?>
<?php
// 	if (isset( $autorutitel[$modecontact['Modecontact']['autorutitel']] ) && $modecontact['Modecontact']['autorutitel'] != 'R' ) {
// 		$numtel = h( $modecontact['Modecontact']['numtel'] );
// 		$numposte = h( $modecontact['Modecontact']['numposte']);
// 	}
// 	else {
// 		$numtel = null;
// 		$numposte = null;
// 	}
// 
// 	if (isset( $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] ) && $modecontact['Modecontact']['autorutiadrelec'] != 'R' )
// 		$adrelec = h( $modecontact['Modecontact']['adrelec']);
// 	else
// 		$adrelec = null;

?>
    <?php
		echo $this->Default2->index(
			$modescontact,
			array(
				'Modecontact.numtel',
				'Modecontact.numposte',
				'Modecontact.nattel',
				'Modecontact.matetel',
				'Modecontact.autorutitel',
				'Modecontact.adrelec',
				'Modecontact.autorutiadrelec'
			),
			array(
				'actions' => array(
					'Modescontact::view' => array( 'domain' => $domain, 'disabled' =>  '( "'.$this->Permissions->check( 'modescontact', 'view' ).'" != "1" )' ),
					'Modescontact::edit' => array( 'domain' => $domain, 'disabled' =>  '( "'.$this->Permissions->check( 'modescontact', 'edit' ).'" != "1" )' )
				),
				'add' => array( 'Modecontact.add' => array( 'controller'=>'modescontact', 'action'=>'add', $foyer_id ) ),
				'options' => $options,
			)
		);

    ?>
</div>
<div class="clearer"><hr /></div>
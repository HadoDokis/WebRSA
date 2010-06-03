<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'motifdemreorient', "Motifsdemsreorients::{$this->action}", true )
    )
?>
<?php
	echo $default->form(
		array(
			'Motifdemreorient.name' => array('required' => true),
		)
	);
?>
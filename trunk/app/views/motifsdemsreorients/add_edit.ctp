<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'motifdemreorient', "Motifsdemsreorients::{$this->action}", true )
        );
    ?>
</h1>

<?php
	echo $default->form(
		array(
			'Motifdemreorient.name',
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'motifsdemsreorients',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
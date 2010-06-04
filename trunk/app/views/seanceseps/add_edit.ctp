<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'seanceep', "Seanceseps::{$this->action}", true )
        );
    ?>
</h1>
<?php
	echo $default->form(
		array(
			'Seanceep.ep_id',
			'Seanceep.structurereferente_id',
			'Seanceep.dateseance',
// 			'Seanceep.finaliseeep',
// 			'Seanceep.finaliseecg',
			'Seanceep.reorientation',
		),
        array(
            'options' => $options
        )
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'seanceseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'partep', "Partseps::{$this->action}", true )
        );
    ?>
</h1>

<?php
	echo $default->form(
		array(
			'Partep.qual' => array( 'options' => array( 'MME' => 'Madame', 'MLE' => 'Mademoiselle', 'MR' => 'Monsieur' ), 'empty' => true ),
			'Partep.nom',
			'Partep.prenom',
			'Partep.tel',
			'Partep.email',
			'Partep.ep_id',
			'Partep.fonctionpartep_id',
			'Partep.rolepartep' => array( 'empty' => true ),
		),
        array(
            'options' => $options
        )
	);
// debug($options);
    echo $default->button(
        'back',
        array(
            'controller' => 'partseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
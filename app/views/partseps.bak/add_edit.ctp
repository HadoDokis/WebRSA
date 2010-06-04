<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'partep', "Partseps::{$this->action}", true )
    );
?>
<?php
	echo $default->form(
		array(
			'Partep.qual' => array( 'options' => array( 'MME' => 'Madame', 'MLE' => 'Mademoiselle', 'MR' => 'Monsieur' ), 'empty' => true, 'required' => true ), // FIXME
			'Partep.nom' => array('required' => true),
			'Partep.prenom' => array('required' => true),
			'Partep.tel' => array('required' => true),
			'Partep.email' => array('required' => true)
		)
	);


?>
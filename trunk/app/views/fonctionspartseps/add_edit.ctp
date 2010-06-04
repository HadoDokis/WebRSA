<h1><?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'fonctionpartep', "Fonctionspartseps::{$this->action}", true )
    );?>
</h1>

<?php
	echo $default->form(
		array(
			'Fonctionpartep.name',
		)
	);
?>
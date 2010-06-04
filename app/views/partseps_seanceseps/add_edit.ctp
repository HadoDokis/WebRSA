<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'partep_seanceep', "PartsepsSeanceseps::{$this->action}", true )
        );
    ?>
</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'PartepSeanceepPresence', [ 'PartepSeanceepRemplacantPartepId' ], 'remplace', false );
    });
</script>
<?php
	echo $default->form(
		array(
			'PartepSeanceep.partep_id',
			'PartepSeanceep.seanceep_id',
			'PartepSeanceep.reponseinvitation',
			'PartepSeanceep.presence',
			'PartepSeanceep.remplacant_partep_id'
		),
        array(
            'options' => $options
        )
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'partseps_seanceseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
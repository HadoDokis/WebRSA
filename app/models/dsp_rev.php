<?php

    class DspRev extends AppModel
    {
        var $name = 'DspRev';

        var $hasMany = array(
			'DetaildifsocRev',
			'DetailaccosocfamRev',
			'DetailaccosocindiRev',
			'DetaildifdispRev',
			'DetailnatmobRev',
			'DetaildiflogRev',
			'DetailmoytransRev',
			'DetaildifsocproRev',
			'DetailprojproRev',
			'DetailfreinformRev',
			'DetailconfortRev'
		);
    }
?>

<?php
    class Originepdo extends AppModel
    {
        var $name = 'Originepdo';

        var $displayField = 'libelle';

        var $actsAs = array(
            'ValidateTranslate'
        );

    }
?>
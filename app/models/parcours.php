<?php
    class Parcours extends AppModel
    {
        var $name = 'Parcours';

        var $belongsTo = array( 'Personne' );
    }
?>
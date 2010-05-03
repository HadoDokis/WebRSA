<?php
    class Suiviaideapretypeaide extends AppModel
    {
        var $name = 'Suiviaideapretypeaide';
        var $useTable = 'suivisaidesaprestypesaides';


        var $belongsTo = array(
            'Suiviaideapre'
        );
    }
?>
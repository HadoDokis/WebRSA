<?php
    class Contactpartenaire extends AppModel
    {
        var $name = 'Contactpartenaire';
        var $useTable = 'contactspartenaires';

        var $belongsTo = array(
            'Partenaire'
        );
    }
?>
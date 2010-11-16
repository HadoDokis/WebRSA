<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'typeaideapre66', "Typesaidesapres66::{$this->action}", true )
    )
?>
<?php
    echo $default->index(
        $typesaidesapres66,
        array(
            'Themeapre66.name',
            'Typeaideapre66.name',
        ),
        array(
            'actions' => array(
                'Typeaideapre66.edit',
                'Typeaideapre66.delete'
            ),
            'add' => 'Typeaideapre66.add'
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'apres66',
            'action'     => 'indexparams'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
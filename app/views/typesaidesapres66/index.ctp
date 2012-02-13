<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'typeaideapre66', "Typesaidesapres66::{$this->action}", true )
    )
?>
<?php
    echo $default2->index(
        $typesaidesapres66,
        array(
            'Themeapre66.name',
            'Typeaideapre66.name',
        ),
        array(
            'actions' => array(
                'Typesaidesapres66::edit',
                'Typesaidesapres66::delete' => array( 'disabled' => '"#Typeaideapre66.occurences#" != "0"' )
            ),
            'add' => 'Typesaidesapres66::add'
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
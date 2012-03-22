<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'motifcernonvalid66', "Motifscersnonvalids66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
        $motifscersnonvalids66,
        array(
            'Motifcernonvalid66.name'
        ),
        array(
            'actions' => array(
                'Motifscersnonvalids66::edit',
                'Motifscersnonvalids66::delete'
            ),
            'add' => 'Motifscersnonvalids66::add'
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'parametrages',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
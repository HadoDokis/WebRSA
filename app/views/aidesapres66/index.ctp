<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'aideapre66', "Aidesapres66::{$this->action}", true )
    )
?>
<?php
    echo $default->index(
        $aidesapres66s,
        array(
            'Themeapre66.name',
            'Aideapre66.name',
        ),
        array(
            'actions' => array(
                'Aideapre66.edit',
                'Aideapre66.delete'
            ),
            'add' => 'Aideapre66.add'
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'apres',
            'action'     => 'indexparams'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
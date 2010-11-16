<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'themeapre66', "Themesapres66::{$this->action}", true )
    )
?>
<?php
    echo $default->index(
        $themesapres66,
        array(
            'Themeapre66.name',
        ),
        array(
            'actions' => array(
                'Themeapre66.edit',
                'Themeapre66.delete'
            ),
            'add' => 'Themeapre66.add'
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
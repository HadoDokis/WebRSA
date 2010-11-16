<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'piececomptable66', "Piecescomptables66::{$this->action}", true )
    )
?>
<?php
    echo $default->index(
        $piecescomptables66,
        array(
            'Piececomptable66.name',
        ),
        array(
            'actions' => array(
                'Piececomptable66.edit',
                'Piececomptable66.delete'
            ),
            'add' => 'Piececomptable66.add'
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
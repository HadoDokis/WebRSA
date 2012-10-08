<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'piecemodeletypecourrierpcg66', "Piecesmodelestypescourrierspcgs66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
        $piecesmodelestypescourrierspcgs66,
        array(
            'Piecemodeletypecourrierpcg66.name',
            'Modeletypecourrierpcg66.name',
            'Piecemodeletypecourrierpcg66.isautrepiece'
        ),
        array(
            'actions' => array(
                'Piecesmodelestypescourrierspcgs66::edit',
                'Piecesmodelestypescourrierspcgs66::delete'
            ),
            'add' => 'Piecesmodelestypescourrierspcgs66::add',
			'options' => $options
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'courrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
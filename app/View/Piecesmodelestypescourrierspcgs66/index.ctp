<?php
    echo $this->Xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'piecemodeletypecourrierpcg66', "Piecesmodelestypescourrierspcgs66::{$this->action}" )
    );
?>
<?php
    echo $this->Default2->index(
        $piecesmodelestypescourrierspcgs66,
        array(
            'Piecemodeletypecourrierpcg66.name',
            'Modeletypecourrierpcg66.name',
            'Piecemodeletypecourrierpcg66.isautrepiece'
        ),
        array(
            'actions' => array(
                'Piecesmodelestypescourrierspcgs66::edit',
                'Piecesmodelestypescourrierspcgs66::delete' => array( 'disabled' => '\'#Piecemodeletypecourrierpcg66.occurences#\' != "0"')
            ),
            'add' => 'Piecesmodelestypescourrierspcgs66::add',
			'options' => $options
        )
    );

    echo $this->Default->button(
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
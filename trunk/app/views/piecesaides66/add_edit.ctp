<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'pieceaide66', "Piecesaides66::{$this->action}", true )
    )
?>
<?php
    echo $default->form(
        array(
            'Pieceaide66.name' => array('required' => true)
        )
    );
?>
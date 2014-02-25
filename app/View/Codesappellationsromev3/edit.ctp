<?php

$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'une appellation métier' : 'Modification d\'une appellation métier' );
$this->set('title_for_layout', $title_for_layout);

if (Configure::read('debug') > 0) {
    echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
}


echo $this->Html->tag('h1', $title_for_layout);
echo $this->Xform->create(null, array('inputDefaults' => array('domain' => 'codeappellationromev3')));


echo $this->Xform->inputs(
    array(
        'fieldset' => false,
        'legend' => false,
        'Codeappellationromev3.id' => array('type' => 'hidden'),
        'Codeappellationromev3.codemetierromev3_id' => array('type' => 'select', 'options' => $codesmetiersromev3, 'empty' => true),
        'Codeappellationromev3.name' => array('type' => 'text')
    )
);

echo $this->Html->tag(
        'div', $this->Xform->button('Enregistrer', array('type' => 'submit'))
        . $this->Xform->button('Annuler', array('type' => 'submit', 'name' => 'Cancel')), array('class' => 'submit noprint')
);

echo $this->Xform->end();
?>
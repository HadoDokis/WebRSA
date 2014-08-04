<?php

$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'un code domaine' : 'Modification d\'un code domaine' );
$this->set('title_for_layout', $title_for_layout);

if (Configure::read('debug') > 0) {
    echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
}


echo $this->Html->tag('h1', $title_for_layout);
echo $this->Xform->create(null, array('inputDefaults' => array('domain' => 'codedomaineproromev3')));


echo $this->Xform->inputs(
    array(
        'fieldset' => false,
        'legend' => false,
        'Codedomaineproromev3.id' => array('type' => 'hidden'),
        'Codedomaineproromev3.codefamilleromev3_id' => array('type' => 'select', 'options' => $codesfamillesromev3, 'empty' => true),
        'Codedomaineproromev3.code' => array('type' => 'text'),
        'Codedomaineproromev3.name' => array('type' => 'text')
    )
);

echo $this->Html->tag(
        'div', $this->Xform->button('Enregistrer', array('type' => 'submit'))
        . $this->Xform->button('Annuler', array('type' => 'submit', 'name' => 'Cancel')), array('class' => 'submit noprint')
);

echo $this->Xform->end();
?>
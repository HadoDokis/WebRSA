<?php

App::import('Controller', 'Dossiers');

class DossiersControllerTest extends CakeTestCase {

    function startTest() {
        $this->Controller = ClassRegistry::init('DossiersController', 'Controller');
        $this->Controller->constructClasses();
        $this->Controller->Component->initialize($this->Controller);
        $this->Controller->Auth->login(
            array(
                'username' => 'webrsa',
                'password' => $this->Controller->Auth->password('webrsa')
            )
        );
    }

    function testView() {
        $this->Controller->view(3);

        $this->assertTrue($this->Controller->viewVars['dossier']['Dossier']);
        $this->assertEqual(3, $this->Controller->viewVars['dossier']['Dossier']['id']);
        $this->assertEqual(12345678901, $this->Controller->viewVars['dossier']['Dossier']['numdemrsa']);
    }
}

?>

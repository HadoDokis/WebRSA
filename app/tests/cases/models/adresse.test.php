<?php
	
	App::import('Model', 'Adresse');
	
	class AdresseTestCase extends CakeTestCase {
	
			function testCoucou() {}
	
	//     function testSave() {
	//         $this->Adresse =& ClassRegistry::init('Adresse');
	// 
	//         $data = array (
	//             'Adresse' => array (
	//                 'numvoie' => '42',
	//                 'typevoie' => 'Rue',
	//                 'nomvoie' => 'Jean Dupont',
	//                 'codepos' => '30000',
	//                 'locaadr' => 'Montpellier',
	//                 'pays' => 'FRA'
	//             )
	//         );
	// 
	//         $this->Adresse->create($data);
	//         $this->Adresse->save($data, true);
	// 
	//         $this->assertTrue($this->Adresse->id);
	//         $objet = $this->Adresse->find(
	//             array('id' => $this->Adresse->id)
	//         );
	//         $this->assertEqual('42', $objet['Adresse']['numvoie']);
	//         $this->assertEqual('Rue', $objet['Adresse']['typevoie']);
	//         $this->assertEqual('Jean Dupont', $objet['Adresse']['nomvoie']);
	//         $this->assertEqual('30000', $objet['Adresse']['codepos']);
	//         $this->assertEqual('Montpellier', $objet['Adresse']['locaadr']);
	//         $this->assertEqual('FRA', $objet['Adresse']['pays']);
	//     }
	// 
	//     function testSaveEmptyNumvoie() {
	//         $this->Adresse =& ClassRegistry::init('Adresse');
	// 
	//         $data = array (
	//             'Adresse' => array (
	//                 'numvoie' => '',
	//                 'typevoie' => 'Rue',
	//                 'nomvoie' => 'Jean Dupont',
	//                 'codepos' => '30000',
	//                 'locaadr' => 'Montpellier',
	//                 'pays' => 'FRA'
	//             )
	//         );
	// 
	//         $this->Adresse->create($data);
	//         $this->Adresse->save($data, true);
	// 
	//         $this->assertFalse($this->Adresse->id);
	//     }
	// 
	//     function testSaveNoNumvoie() {
	//         $this->Adresse =& ClassRegistry::init('Adresse');
	// 
	//         $data = array (
	//             'Adresse' => array (
	//                 'typevoie' => 'Rue',
	//                 'nomvoie' => 'Jean Dupont',
	//                 'codepos' => '30000',
	//                 'locaadr' => 'Montpellier',
	//                 'pays' => 'FRA'
	//             )
	//         );
	// 
	//         $this->Adresse->create($data);
	//         $this->Adresse->save($data, true);
	// 
	//         $this->assertFalse($this->Adresse->id);
	//     }
	// 
	//     function testSaveLongNumvoie() {
	//         $this->Adresse =& ClassRegistry::init('Adresse');
	// 
	//         $data = array (
	//             'Adresse' => array (
	//                 'numvoie' => '13245678901234567890',
	//                 'typevoie' => 'Rue',
	//                 'nomvoie' => 'Jean Dupont',
	//                 'codepos' => '30000',
	//                 'locaadr' => 'Montpellier',
	//                 'pays' => 'FRA'
	//             )
	//         );
	// 
	//         $this->Adresse->create($data);
	//         $this->Adresse->save($data, true);
	// 
	//         $this->assertFalse($this->Adresse->id);
	//     }
	
	}
	
?>
<?php  
 class ArticleFixture extends CakeTestFixture { 
      var $name = 'Article'; 

      var $fields = array( 
          'id' => array('type' => 'integer', 'key' => 'primary'), 
          'titre' => array('type' => 'string', 'length' => 255, 'null' => false), 
          'contenu' => 'text', 
          'publiable' => array('type' => 'integer', 'default' => '0', 'null' => false), 
          'created' => 'datetime', 
          'updated' => 'datetime' 
      ); 
      var $records = array( 
          array ('id' => 1, 'titre' => 'Premier Article', 'contenu' => 'Corps du premier Article', 'publiable' => '1', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'), 
          array ('id' => 2, 'titre' => 'Second Article', 'contenu' => 'Corps du second Article', 'publiable' => '1', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'), 
          array ('id' => 3, 'titre' => 'Troisième Article', 'contenu' => 'Corps du troisième Article', 'publiable' => '1', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'), 
      ); 
 } 
 ?> 
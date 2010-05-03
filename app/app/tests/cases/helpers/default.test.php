<?php
	require_once( dirname( __FILE__ ).'/../cake_app_helper_test_case.php' );

	App::import('Helper',array(
			'Type',
			'Html',
			'Form',
			'Locale',
			'Xpaginator',
			'Xform',
			'Default',
			'Time'
		)
	);

	class DefaultTestCase extends CakeAppHelperTestCase
	{
		public $Default=null;

		public function testLabel() {
			$this->assertEqual(
				__d( 'user', 'User.username', true ),
				$this->Default->label( 'User.username' )
			);

			$this->assertEqual(
				'Foo',
				$this->Default->label( 'User.username', array( 'label' => 'Foo' ) )
			);

			$this->assertEqual(
				__d( 'foo', 'User.username', true ),
				$this->Default->label( 'User.username', array( 'domain' => 'foo' ) )
			);

			$this->assertEqual(
				__d( 'user', 'User.username', true ),
				$this->Default->label( 'User.0.username' )
			);

			$this->assertEqual(
				__d( 'post_tag', 'PostTag.freu', true ),
				$this->Default->label( 'PostTag.0.freu' )
			);
		}

		/**
		*
		*/

		public function testFormatWithoutTag() {
			$user = array(
				'User' => array(
					'id' => 1000,
					'username' => 'cbuffin',
					'date_deb_hab' => '1979-01-24'
				)
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.date_deb_hab' ),
				'24/01/1979'
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.date_deb_hab', array( 'type' => 'date' ) ),
				'24/01/1979'
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.username' ),
				'cbuffin'
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.id' ),
				'1&nbsp;000'
			);
		}

		/**
		*
		*/

		public function testFormatWithTag() {
			$user = array(
				'User' => array(
					'id' => 1000,
					'username' => 'cbuffin',
					'date_deb_hab' => '1979-01-24'
				)
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.username', array( 'tag' => 'span' ) ),
				'<span class="string">cbuffin</span>'
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.date_deb_hab', array( 'tag' => 'span', 'class' => 'foo', 'type' => 'date' ) ),
				'<span class="foo date">24/01/1979</span>'
			);

			$this->assertEqual(
				$this->Default->format( $user, 'User.id', array( 'tag' => 'span' ) ),
				'<span class="integer number positive">1&nbsp;000</span>'
			);
		}

		/**
		*
		*/

		public function testButton() {
			$this->assertEqual(
				$this->Default->button( 'print', '/' ),
				'<a href="/" class="widget button print enabled "><img src="img/icons/printer.png" alt="" /> Imprimer</a>'
			);
		}

		/**
		*
		*/

		public function testIndex() {
			$users = array(
				array(
					'User' => array(
						'id' => 1,
						'username' => 'cbuffin',
						'date_deb_hab' => '1979-01-24'
					)
				)
			);

			$this->Default->params['controller'] = 'users';
			$this->Default->params['pass'] = array();
			$this->Default->params['named'] = array();

			$result = $this->Default->index(
				$users,
				array(
					'User.username',
					'User.date_deb_hab' => array( 'type' => 'date' ),
				)
			);

			$expected = '<table><thead><tr><th>User.username</th> <th>User.date_deb_hab</th></tr></thead><tbody><tr class="odd" id="UsersRow1"><td class="string">cbuffin</td><td class="date">24/01/1979</td></tr></tbody></table>';

			$this->assertEqual( $result, $expected );
		}

		/**
		*
		*/

		public function testView() {
			$user = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
					'date_deb_hab' => '1979-01-24'
				)
			);

			$this->Default->params['controller'] = 'users';
			$this->Default->params['pass'] = array();
			$this->Default->params['named'] = array();

			$result = $this->Default->view(
				$user,
				array(
					'User.username',
					'User.date_deb_hab' => array( 'type' => 'date' ),
				)
			);

			$expected = '<dl class="view"><dt class="odd">User.username</dt><dd class="odd string">cbuffin</dd><dt class="even">User.date_deb_hab</dt><dd class="even date">24/01/1979</dd></dl>';

			$this->assertEqual( $result, $expected );

			//------------------------------------------------------------------

			$result = $this->Default->view(
				$user,
				array(
					'User.username',
					'User.date_deb_hab' => array( 'type' => 'date' ),
				),
				array(
					'widget' => 'table'
				)
			);

			$expected = '<table class="view"><tbody><tr class="odd"><th class="odd">User.username</th><td class="odd string">cbuffin</td></tr><tr class="even"><th class="even">User.date_deb_hab</th><td class="even date">24/01/1979</td></tr></tbody></table>';

			$this->assertEqual( $result, $expected );
		}

		/**
		*
		*/

		public function testForm() {
			$this->Default->params['controller'] = 'users';
			$this->Default->params['pass'] = array();
			$this->Default->params['named'] = array();

			// -----------------------------------------------------------------

			$result = $this->Default->form(
				array(
					'User.username',
				)
			);

			$expected = '<form method="post" action="'.Router::url( '/', true ).'"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset><div class="input text"><label for="UserUsername">User.username</label><input name="data[User][username]" type="text" value="" id="UserUsername" /></div><div class="submit"><input type="submit" value="Enregistrer" class="input submit" /></div></form>';
			$this->assertEqual( $result, $expected );


			// -----------------------------------------------------------------

			$result = $this->Default->form(
				array(
					'User.username',
				),
				array(
					'submit' => 'Search'
				)
			);

			$expected = '<form method="post" action="'.Router::url( '/', true ).'"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset><div class="input text"><label for="UserUsername">User.username</label><input name="data[User][username]" type="text" value="" id="UserUsername" /></div><div class="submit"><input type="submit" value="Rechercher" class="input submit" /></div></form>';
			$this->assertEqual( $result, $expected );


			// -----------------------------------------------------------------


			$result = $this->Default->form(
				array(
					'User.username',
				),
				array(
					'submit' => array(
						'Save' => 'submit',
						'Cancel' => array( 'name' => 'cancel' ),
						'Reset' => 'reset'
					)
				)
			);
			$expected = '<form method="post" action="'.Router::url( '/', true ).'"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset><div class="input text"><label for="UserUsername">User.username</label><input name="data[User][username]" type="text" value="" id="UserUsername" /></div><div class="submit"><input type="submit" value="Enregistrer" class="input submit" /> <input type="submit" value="Annuler" name="cancel" class="input submit" /> <input type="reset" value="Remise à zéro" class="input reset" /></div></form>';
			$this->assertEqual( $result, $expected );
		}

		/**
		*
		*/

		public function testGroupColumns() {
			$thead = '<thead><tr><th><a href="#id" class=" sort desc">Id</a></th> <th>Item.fullname</th> <th><a href="#name_a">Nom a.</a></th> <th>Nom b.</th> <th>Version a.</th> <th>Version n.</th> <th>Modifiable a.</th> <th>Modifiable b.</th> <th>Téléphone</th> <th>Télécopie</th> <th>Montant</th><th class="actions" colspan="2">Actions</th></th></tr></thead>';

			$result = $this->Default->groupcolumns(
				$thead,
				array(
					'Nom a et nom b' => array( 2, 3 ),
					'Item.version' => array( 4, 5 ),
					'Item.modifiable' => array( 6, 7 ),
				)
			);

			$expected = '<thead><tr><th rowspan="2"><a href="#id" class=" sort desc">Id</a></th><th rowspan="2">Item.fullname</th><th colspan="2">Nom a et nom b</th><th colspan="2">Item.version</th><th colspan="2">Item.modifiable</th><th rowspan="2">Téléphone</th><th rowspan="2">Télécopie</th><th rowspan="2">Montant</th><th rowspan="2" class="actions" colspan="2">Actions</th></tr><tr><th><a href="#name_a">Nom a.</a></th><th>Nom b.</th><th>Version a.</th><th>Version n.</th><th>Modifiable a.</th><th>Modifiable b.</th></tr></thead>';

			$this->assertEqual( $result, $expected );
		}

		/**
		* TODO
		*/

		/*public function testMenu() {
			$menu = array(
				'Référents' => array(
					'Liste' => array( 'controller' => 'referents', 'action' => 'index' ),
					'Synthèse' => array(
						'Référent 1' => array( 'controller' => 'referents', 'action' => 'demandes_reorient', 1 ),
						'Référent 2' => array( 'controller' => 'referents', 'action' => 'demandes_reorient', 2 )
					)
				),
				'Demandes de réorientation' => array(
					'Liste' => array( 'controller' => 'demandesreorient', 'action' => 'index' ),
				),
				'Équipe pluridisciplinaire' => array(
					'Liste' => array( 'controller' => 'eps', 'action' => 'index' ),
					'Cohorte demandes de réorientation' => array( 'controller' => 'precosreorient', 'action' => 'index', 1 ),
				),
				'Conseil général' => array(
					'Cohorte demandes de réorientation' => array( 'controller' => 'precosreorient', 'action' => 'conseil', 1 ),
				),
				'Administration' => array(
					'Demandes de réorientation' => array(
						'Motifs' => array( 'controller' => 'motifsdemsreorients', 'action' => 'index' ),
					),
					'Équipes pluridisciplinaires' => array(
						'Participants' => array( 'controller' => 'partseps', 'action' => 'index' ),
						'Rôles participants' => array( 'controller' => 'rolespartseps', 'action' => 'index' ),
					)
				),
			);

			$result = $this->Default->menu( $menu );
// 			debug( htmlentities( utf8_decode( $result ) ) );
			echo $result;
		}*/
	}
?>
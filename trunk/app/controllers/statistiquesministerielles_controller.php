<?phpclass StatistiquesministeriellesController extends AppController{	var $name = 'Statistiquesministerielles';	var $uses = array( 'Serviceinstructeur', 'Statistiquesministerielle');	public function beforeFilter() {		parent::beforeFilter();		$typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );		$this->set( 'typeservice', $typeservice );	}	/**	 * @return unknown_type	 */	function _filtre()	{		$inputs = array(			'localisation'	=> null,			'service'		=> null,			'annee'			=> null		);		if( !empty($this->data['Statistiquesministerielle']['localisation'])) {			$input['localisation'] = $this->data['Statistiquesministerielle']['localisation'];		}		if( !empty($this->data['Statistiquesministerielle']['service'])) {			$input['localisation'] = $this->data['Statistiquesministerielle']['service'];		}		if( !empty($this->data['Statistiquesministerielle']['date']['year'])) {			$input['localisation'] = $this->data['Statistiquesministerielle']['date']['year'];		}		return$inputs;	}	function indicateursOrientation()	{	}	/**		* Localité /  Service instructeur / Année		*/	function indicateursOrganismes()	{		if( !empty( $this->data ) ) {			$args = $this->_filtre();			$results = $this->Statistiquesministerielle->indicateursOrganismes($args);			$this->set( compact( 'results' ) );			}					}	function indicateursDelais()	{	}	function indicateursReorientation()	{	}	function indicateursMotifsReorientation()	{	}	function indicateursCaracteristiquesContrats()	{	}	function indicateursNatureContrats()	{	}}?>
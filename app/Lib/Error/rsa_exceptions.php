<?php
	/**
	 * 
	 */
	class RsaException extends CakeException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code, $params = array( ) ) {
			parent::__construct( $message, $code );
			if( $params ) {
				$this->params = $params;
			}
		}

	}
	/**
	 *
	 */
	class notWritableDirsException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class missingBinariesException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class webrsaIncException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class incompleteUserException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class incompleteApreException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class incompleteStructureException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class LockedDossierException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class lockedActionException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class dateHabilitationUserException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 401, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class error403Exception extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 403, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class error500Exception extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 500, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class invalidParameterException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 404, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
	/**
	 *
	 */
	class invalidParamForTokenException extends RsaException
	{

		/**
		 * Constructor
		 *
		 * @param type $message
		 * @param type $code
		 * @param type $params
		 */
		public function __construct( $message, $code = 404, $params = array( ) ) {
			parent::__construct( $message, $code, $params );
		}

	}
?>

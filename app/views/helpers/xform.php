<?php
	App::import( 'Helper', 'Form' );
 	//define( 'REQUIRED_MARK', '<abbr class="required" title="Champ obligatoire">*</abbr>' );

    class XformHelper extends FormHelper
    {
        var $helpers = array( 'Html' );

        /** ********************************************************************
        *
        ** ********************************************************************/

		function create( $model = null, $options = array() ) {
			if( !array_any_key_exists( array( 'url', 'controller', 'action' ), $options ) ) {
				$options['url'] = Router::url( null, true );
			}
			return parent::create( $model, $options );
		}

        /** ********************************************************************
        *
        ** ********************************************************************/

		function submit( $caption = null, $options = array() ) {
			return parent::submit( __( $caption, true ), $options );
		}

        /** ********************************************************************
        *
        ** ********************************************************************/

		function required( $label ) {
			return h( $label ).' '.REQUIRED_MARK;
		}

        /** ********************************************************************
        *
        ** ********************************************************************/

        function _label( $label, $options ) {
            $type = Set::extract( $options, 'type' );
            if( $type == 'hidden' ) {
                return false;
            }

            $domain = Set::extract( $options, 'domain' );

            if( !empty( $label ) ) {
                $msgid = preg_replace( '/\.[0-9]+\./', '.', $label );
				if( empty( $domain ) || ( $domain == 'default' ) ) {
					$label = __( $msgid, true );
				}
				else {
					$label = __d( $domain, $msgid, true );
				}
                if( isset( $options['required'] ) && ( $options['required'] == true ) ) {
                    $label = $this->required( $label );
                }
            }

			return $label;
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function input( $fieldName, $options = array() ) {
            $defaultOptions['label'] = $this->_label( $fieldName, $options );
            unset( $options['required'] );
			unset( $options['domain'] );
            return parent::input( $fieldName, Set::merge( $defaultOptions, $options ) );
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function enum( $fieldName, $options = array() ) {
			$domain = strtolower( preg_replace( '/^([^\.]+)\..*$/', '\1', $fieldName ) );
			$defaultOptions = array(
				'domain' => $domain,
				'type' => 'select',
				'empty' => ''
			);
            return self::input( $fieldName, Set::merge( $defaultOptions, $options ) );
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function multiple( $fieldName, $options = array() ) {
            $errors = Set::extract( parent::validationErrors, $fieldName );
            $htmlAttributes = array( 'class' => 'multiple' );
            if( !empty( $errors ) ) {
                $htmlAttributes['class'] = $htmlAttributes['class'].' error';
            }

            $label = Set::extract( $options, 'label' );
            if( empty( $label ) ) {
                $label =  $this->_label( $fieldName, $options );
            }

            return $this->Html->tag(
                'fieldset',
                $this->Html->tag( 'legend', $label ).
                    $this->input( $fieldName,  Set::merge( $options, array( 'label' => false, 'div' => false, 'multiple' => 'multiple' ) ) ),
                $htmlAttributes
            );
        }

        /** ********************************************************************
        * Returns an array of formatted OPTION/OPTGROUP elements
        * @access private
        * @return array
        ** ********************************************************************/
        // FIXME -> WTFBBQ ?
        function __selectOptions($elements = array(), $selected = null, $parents = array(), $showParents = null, $attributes = array()) {
            $select = array();
            $attributes = array_merge(array('escape' => true, 'style' => null), $attributes);
            $selectedIsEmpty = ($selected === '' || $selected === null);
            $selectedIsArray = is_array($selected);

            foreach ($elements as $name => $title) {
                $htmlOptions = array();
                if (is_array($title) && (!isset($title['name']) || !isset($title['value']))) {
                    if (!empty($name)) {
                        if ($attributes['style'] === 'checkbox') {
                            $select[] = $this->Html->tags['fieldsetend'];
                        } else {
                            $select[] = $this->Html->tags['optiongroupend'];
                        }
                        $parents[] = $name;
                    }
                    $select = array_merge($select, $this->__selectOptions(
                        $title, $selected, $parents, $showParents, $attributes
                    ));

                    if (!empty($name)) {
                        if ($attributes['style'] === 'checkbox') {
                            $select[] = sprintf($this->Html->tags['fieldsetstart'], $name);
                        } else {
                            $select[] = sprintf($this->Html->tags['optiongroup'], $name, '');
                        }
                    }
                    $name = null;
                } elseif (is_array($title)) {
                    $htmlOptions = $title;
                    $name = $title['value'];
                    $title = $title['name'];
                    unset($htmlOptions['name'], $htmlOptions['value']);
                }

                if ($name !== null) {
                    // FIXME: cast to string ?!!
                    if ((!$selectedIsEmpty && $selected == $name) || ($selectedIsArray && in_array((string)$name, $selected))) {
                        if ($attributes['style'] === 'checkbox') {
                            $htmlOptions['checked'] = true;
                        } else {
                            $htmlOptions['selected'] = 'selected';
                        }
                    }

                    if ($showParents || (!in_array($title, $parents))) {
                        $title = ($attributes['escape']) ? h($title) : $title;

                        if ($attributes['style'] === 'checkbox') {
                            $htmlOptions['value'] = $name;

                            $tagName = Inflector::camelize(
                                $this->model() . '_' . $this->field().'_'.Inflector::underscore($name)
                            );
                            $htmlOptions['id'] = $tagName;
                            $label = array('for' => $tagName);

                            if (isset($htmlOptions['checked']) && $htmlOptions['checked'] === true) {
                                $label['class'] = 'selected';
                            }

                            list($name) = array_values($this->__name());

                            if (empty($attributes['class'])) {
                                $attributes['class'] = 'checkbox';
                            }
                            $label = $this->label(null, $title, $label);
                            $item = sprintf(
                                $this->Html->tags['checkboxmultiple'], $name,
                                $this->_parseAttributes($htmlOptions)
                            );
                            $select[] = $this->Html->div($attributes['class'], $item . $label);
                        } else {
                            $select[] = sprintf(
                                $this->Html->tags['selectoption'],
                                $name, $this->_parseAttributes($htmlOptions), $title
                            );
                        }
                    }
                }
            }

            return array_reverse($select, true);
        }
    }
?>
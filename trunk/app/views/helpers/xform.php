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

        function address( $fieldName, $options = array() ) {
            $options['type'] = 'textarea';
            $options['rows'] = ( isset( $options['rows'] ) ? $options['rows'] : '3' );
            $options['label'] = false;
            $options['div'] = false;
            $options['required'] = ( isset( $options['required'] ) ? $options['required'] : false );

            $label = $this->Html->tag( 'label', $this->_label( $fieldName, $options ) );
            $textarea = $this->input( $fieldName, $options );
            return $this->Html->tag( 'div', $label.$textarea, array( 'class' => 'input textarea address' ) );
        }

        /** ********************************************************************
        *   FIXME: en cas de fieldset (type => options), il n'y a pas de
        *   traduction automatique
        ** ********************************************************************/

        function enum( $fieldName, $options = array() ) {
			$domain = strtolower( preg_replace( '/^([^\.]+)\..*$/', '\1', $fieldName ) );
			$defaultOptions = array(
				'domain' => $domain,
				'type' => 'select',
                'required' => false,
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

        /**
        * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
        *
        * Attributes:
        *
        * - 'monthNames' If set and false numbers will be used for month select instead of text.
        * - 'minYear' The lowest year to use in the year select
        * - 'maxYear' The maximum year to use in the year select
        * - 'interval' The interval for the minutes select. Defaults to 1
        * - 'separator' The contents of the string between select elements. Defaults to '-'
        *
        * @param string $fieldName Prefix name for the SELECT element
        * @param string $dateFormat DMY, MDY, YMD or NONE.
        * @param string $timeFormat 12, 24, NONE
        * @param string $selected Option which is selected.
        * @param string $attributes array of Attributes
        * @param bool $showEmpty Whether or not to show an empty default value.
        * @return string The HTML formatted OPTION element
        */

        function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = array(), $showEmpty = true) {
            $year = $month = $day = $hour = $min = $meridian = null;

            if (empty($selected)) {
                $selected = $this->value($fieldName);
            }

            if ($selected === null && $showEmpty != true) {
                $selected = time();
            }

            if (!empty($selected)) {
                if (is_array($selected)) {
                    extract($selected);
                } else {
                    if (is_numeric($selected)) {
                        $selected = strftime('%Y-%m-%d %H:%M:%S', $selected);
                    }
                    $meridian = 'am';
                    $pos = strpos($selected, '-');
                    if ($pos !== false) {
                        $date = explode('-', $selected);
                        $days = explode(' ', $date[2]);
                        $day = $days[0];
                        $month = $date[1];
                        $year = $date[0];
                    } else {
                        $days[1] = $selected;
                    }

                    if ($timeFormat != 'NONE' && !empty($timeFormat)) {
                        $time = explode(':', $days[1]);
                        $check = str_replace(':', '', $days[1]);

                        if (($check > 115959) && $timeFormat == '12') {
                            $time[0] = $time[0] - 12;
                            $meridian = 'pm';
                        } elseif ($time[0] == '00' && $timeFormat == '12') {
                            $time[0] = 12;
                        } elseif ($time[0] > 12) {
                            $meridian = 'pm';
                        }
                        if ($time[0] == 0 && $timeFormat == '12') {
                            $time[0] = 12;
                        }
                        $hour = $time[0];
                        $min = $time[1];
                    }
                }
            }

            $elements = array('Day','Month','Year','Hour','Minute','Meridian');
            $defaults = array(
                'minYear' => null, 'maxYear' => null, 'separator' => '-',
                'interval' => 1, 'monthNames' => true
            );
            $attributes = array_merge($defaults, (array) $attributes);
            if (isset($attributes['minuteInterval'])) {
                $attributes['interval'] = $attributes['minuteInterval'];
                unset($attributes['minuteInterval']);
            }

            $minYear = $attributes['minYear'];
            $maxYear = $attributes['maxYear'];
            $separator = $attributes['separator'];
            $interval = $attributes['interval'];
            $monthNames = $attributes['monthNames'];
            $attributes = array_diff_key($attributes, $defaults);

            if (isset($attributes['id'])) {
                if (is_string($attributes['id'])) {
                    // build out an array version
                    foreach ($elements as $element) {
                        $selectAttrName = 'select' . $element . 'Attr';
                        ${$selectAttrName} = $attributes;
                        ${$selectAttrName}['id'] = $attributes['id'] . $element;
                    }
                } elseif (is_array($attributes['id'])) {
                    // check for missing ones and build selectAttr for each element
                    foreach ($elements as $element) {
                        $selectAttrName = 'select' . $element . 'Attr';
                        ${$selectAttrName} = $attributes;
                        ${$selectAttrName}['id'] = $attributes['id'][strtolower($element)];
                    }
                }
            } else {
                // build the selectAttrName with empty id's to pass
                foreach ($elements as $element) {
                    $selectAttrName = 'select' . $element . 'Attr';
                    ${$selectAttrName} = $attributes;
                }
            }

            $opt = '';

            if ($dateFormat != 'NONE') {
                $selects = array();
                foreach (preg_split('//', $dateFormat, -1, PREG_SPLIT_NO_EMPTY) as $char) {
                    switch ($char) {
                        case 'Y':
                            $selects[] = $this->year(
                                $fieldName, $minYear, $maxYear, $year, $selectYearAttr, $showEmpty
                            );
                        break;
                        case 'M':
                            $selectMonthAttr['monthNames'] = $monthNames;
                            $selects[] = $this->month($fieldName, $month, $selectMonthAttr, $showEmpty);
                        break;
                        case 'D':
                            $selects[] = $this->day($fieldName, $day, $selectDayAttr, $showEmpty);
                        break;
                    }
                }
                $opt = implode($separator, $selects);
            }
            if (!empty($interval) && $interval > 1 && !empty($min)) {
                $min = round($min * (1 / $interval)) * $interval;
            }
            $selectMinuteAttr['interval'] = $interval;
            switch ($timeFormat) {
                case '24':
                    $opt .= $this->hour($fieldName, true, $hour, $selectHourAttr, $showEmpty) . ':' .
                    $this->minute($fieldName, $min, $selectMinuteAttr, $showEmpty);
                break;
                case '12':
                    $opt .= $this->hour($fieldName, false, $hour, $selectHourAttr, $showEmpty) . ':' .
                    $this->minute($fieldName, $min, $selectMinuteAttr, $showEmpty) . ' ' .
                    $this->meridian($fieldName, $meridian, $selectMeridianAttr, $showEmpty);
                break;
                case 'NONE':
                default:
                    $opt .= '';
                break;
            }
            return $opt;
        }

        /**
        * Returns a SELECT element for hours.
        *
        * @param string $fieldName Prefix name for the SELECT element
        * @param boolean $format24Hours True for 24 hours format
        * @param string $selected Option which is selected.
        * @param array $attributes List of HTML attributes
        * @param mixed $showEmpty True to show an empty element, or a string to provide default empty element text
        * @return string
        */

        function hour($fieldName, $format24Hours = false, $selected = null, $attributes = array(), $showEmpty = true) {
            if ((empty($selected) || $selected === true) && $value = $this->value($fieldName)) {
                if (is_array($value)) {
                    extract($value);
                    $selected = $hour;
                } else {
                    if (empty($value)) {
                        if (!$showEmpty) {
                            $selected = 'now';
                        }
                    } else {
                        $selected = $value;
                    }
                }
            } else {
                $value = $selected;
            }

            if (strlen($selected) > 2) {
                if ($format24Hours) {
                    $selected = date('H', strtotime($value));
                } else {
                    $selected = date('g', strtotime($value));
                }
            } elseif ($selected === false) {
                $selected = null;
            }
            $options = array();
            if( isset( $attributes['hourRange'] ) ) {
                $options['hourRange'] = $attributes['hourRange'];
                unset( $attributes['hourRange'] );
            }
            return $this->select(
                $fieldName . ".hour",
                $this->__generateOptions($format24Hours ? 'hour24' : 'hour', $options),
                $selected, $attributes, $showEmpty
            );
        }

        /**
        * Generates option lists for common <select /> menus
        * @access private
        */

        function __generateOptions($name, $options = array()) {
            if (!empty($this->options[$name])) {
                return $this->options[$name];
            }
            $data = array();

            $min = Set::classicExtract( $options, 'hourRange.0' );
            $max = Set::classicExtract( $options, 'hourRange.1' );

            switch ($name) {
                case 'hour':
                    $min = ( empty( $min ) ? 0 : $min );
                    $max = ( empty( $max ) ? 12 : $max );

                    for ($i = $min; $i <= $max; $i++) {
                        $data[sprintf('%02d', $i)] = $i;
                    }
                break;
                case 'hour24':
                    $min = ( empty( $min ) ? 0 : $min );
                    $max = ( empty( $max ) ? 23 : $max );

                    for ($i = $min; $i <= $max; $i++) {
                        $data[sprintf('%02d', $i)] = $i;
                    }
                break;
                default:
                    return parent::__generateOptions($name, $options);
            }
            $this->__options[$name] = $data;
            return $this->__options[$name];
        }
    }
?>
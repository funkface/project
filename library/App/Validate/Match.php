<?php

/**
 * Validate that the values of the current and given element match
 */

class App_Validate_Match extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';

    /**
     * Form Element value to match
     *
     * @var string
     */
    protected $_matchValue = null;

    /**
     * Override invalid message
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Values do not match'
    );

    /**
     * Receive element to match against current (could have used second $context param
     * in isValid but this does not allow pre-filtering).
     *
     * @param $element
     * @return unknown_type
     */
    public function __construct($element)
    {
        $this->_matchValue = $element->getValue();
    }

    public function isValid($value)
    {
        $value = (string) $value;
        $this->_setValue($value);

        if ($this->_matchValue) {
            if (isset($this->_matchValue)
                && ($value == $this->_matchValue))
            {
                return true;
            }
        } elseif (is_string($context) && ($value == $context)) {
            return true;
        }

        $this->_error(self::NOT_MATCH);
        return false;
    }
}
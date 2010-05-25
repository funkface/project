<?php

class Zend_View_Helper_LogoNoSubgroupWarning
{
    protected $_message = 'This logo will not display (other logos in this group are in sub-groups)';

    function logoNoSubgroupWarning($itemSubgroupTitle, $group)
    {
        // This item is in a subgroup - so it will display (no need to check)
        if ($itemSubgroupTitle != 'nosubgroup') {
            return;
        }

        // This item is not in a subgroup, so return warning if any other items in the group are in a subgroup
        foreach ($group as $subgroupTitle => $items) {
            if ($subgroupTitle != 'nosubgroup') {
                return $this->_message;
            }
        }
    }
}
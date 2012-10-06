<?php

/**
 * a statement that explains how to select data by a set of conditions, like a 'WHERE' statement in SQL
 * @version 0.1.20110313
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
interface ICondition {

    /**
     * add an expression into a set of conditions that selects special records in the database
     * @param string $c
     * an expresion that specifies a condition
     * @param int $l
     * an integer code for logical operators 'AND' and 'OR',
     * which connects the new condition and old ones;<br />
     * constants and their values are listed below:
     * <ul>
     * <li>0 - IBC1_LOGICAL_AND</li>
     * <li>1 - IBC1_LOGICAL_OR</li>
     * </ul>
     */
    public function AddCondition($c, $l = IBC1_LOGICAL_AND);

    /**
     * remove all conditions
     */
    public function ClearConditions();

    /**
     * get the number of conditions
     * @return int
     */
    public function ConditionCount();
}

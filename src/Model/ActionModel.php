<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 */

namespace Netzmacht\Contao\Workflow\Model;

/**
 * Class ActionModel provides access to tl_workflow_action table.
 *
 * @package Netzmacht\Contao\Workflow\Contao\Model
 * @property int $id
 * @property int $pid
 */
class ActionModel extends \Model
{
    /**
     * Action model table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_workflow_action';
}
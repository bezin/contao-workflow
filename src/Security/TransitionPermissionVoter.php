<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\ContaoWorkflowBundle\Security;

use Netzmacht\Workflow\Flow\Transition;

/**
 * Class WorkflowPermissionVoter
 *
 * @deprecated Deprecated since version 2.3.0 and will be removed in version 3.0.0.
 */
final class TransitionPermissionVoter extends AbstractPermissionVoter
{
    /**
     * {@inheritDoc}
     */
    protected function getSubjectClass(): string
    {
        return Transition::class;
    }
}

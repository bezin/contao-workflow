<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2020 netzmacht David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\ContaoWorkflowBundle\EventListener\Workflow;

use Contao\Model;
use Netzmacht\Contao\Toolkit\Assertion\AssertionFailed;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Netzmacht\ContaoWorkflowBundle\PropertyAccess\PropertyAccessManager;
use Netzmacht\ContaoWorkflowBundle\Workflow\Flow\Action\Notification\BuildNotificationTokensEvent;

/**
 * Class AddFormattedEntityNotificationTokensListener enriches the notifications with formatted values.
 */
final class AddFormattedEntityNotificationTokensListener
{
    /**
     * Property access manager.
     *
     * @var PropertyAccessManager
     */
    private $propertyAccessManager;

    /**
     * Data container manager.
     *
     * @var DcaManager
     */
    private $dcaManager;

    /**
     * Constructor.
     *
     * @param PropertyAccessManager $propertyAccessManager Property access manager.
     * @param DcaManager            $dcaManager            Data container manager.
     */
    public function __construct(PropertyAccessManager $propertyAccessManager, DcaManager $dcaManager)
    {
        $this->dcaManager            = $dcaManager;
        $this->propertyAccessManager = $propertyAccessManager;
    }

    /**
     * Invoke.
     *
     * @param BuildNotificationTokensEvent $event The subscribed event.
     *
     * @return void
     */
    public function __invoke(BuildNotificationTokensEvent $event): void
    {
        $entity = $event->getItem()->getEntity();
        if (!$this->propertyAccessManager->supports($entity)) {
            return;
        }

        try {
            $formatter = $this->dcaManager->getFormatter($event->getItem()->getEntityId()->getProviderName());
        } catch (AssertionFailed $exception) {
            return;
        }

        foreach ($this->propertyAccessManager->provideAccess($entity) as $key => $value) {
            try {
                $event->addToken('formatted_' . $key, $formatter->formatValue($key, $value));
            } catch (\Exception $exception) {
                // Skip if any error occurs
            }
        }
    }
}

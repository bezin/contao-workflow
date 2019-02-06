<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\ContaoWorkflowExampleBundle\Workflow\Action;

use Contao\Config;
use Netzmacht\ContaoWorkflowBundle\Workflow\Flow\Action\AbstractAction;
use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Context\Properties;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Transition;
use Patchwork\Utf8;
use Swift_Mailer;

final class SendEmailNotificationAction extends AbstractAction
{
    /**
     * Recipient of the message.
     *
     * @var string
     */
    private $recipient;

    /**
     * Default message.
     *
     * @var string
     */
    private $message;

    /**
     * Mailer service.
     *
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        Swift_Mailer $mailer,
        string $name,
        string $label,
        string $recipient,
        string $message,
        array $config = []
    ) {
        parent::__construct($name, $label, $config);

        $this->mailer    = $mailer;
        $this->recipient = $recipient;
        $this->message   = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredPayloadProperties(Item $item): array
    {
        return [$this->messageField()];
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Item $item, Context $context): bool
    {
        $payload = $context->getPayload();
        if (!$payload->has($this->messageField())) {
            return false;
        }

        return Utf8::strlen($payload->get($this->messageField())) >= 3;
    }

    /**
     * {@inheritDoc}
     */
    public function transit(Transition $transition, Item $item, Context $context): void
    {
        $body    = $this->message . "\n\n" . $context->getPayload()->get($this->messageField());
        $message = (new \Swift_Message($this->createSubjectMessage($transition, $item)))
            ->setFrom(Config::get('adminEmail'))
            ->setTo($this->recipient)
            ->setBody($body, 'text/html');

        // The send email notification is triggered as post action. It means that the transition is already done,
        // you create an failed transition anymore. So instead, we log the failed recipients.
        $this->mailer->send($message, $failedRecipients);
        $this->logSuccess($context->getProperties(), $failedRecipients);
    }

    /**
     * Get name of the message field.
     *
     * @return string
     */
    private function messageField(): string
    {
        return $this->getName() . '_message';
    }

    /**
     * Create the subject message.
     *
     * @param Transition $transition
     * @param Item       $item
     *
     * @return string
     */
    private function createSubjectMessage(Transition $transition, Item $item): string
    {
        $workflow = $transition->getWorkflow();

        return sprintf(
            '%s: step %s reached with transition %s',
            $workflow->getLabel(),
            $workflow->getStep($item->getCurrentStepName())->getLabel(),
            $transition->getLabel()
        );
    }

    /**
     * Log success or error as state data.
     *
     * @param Properties $properties       State properties.
     * @param array      $failedRecipients Failed recipients.
     *
     * @return void
     */
    private function logSuccess(Properties $properties, array $failedRecipients): void
    {
        if ($failedRecipients) {
            $log = [
                'state'      => 'error',
                'recipients' => $failedRecipients,
            ];
        } else {
            $log = ['state' => 'success'];
        }

        $properties->set($this->getName(), $log);
    }
}
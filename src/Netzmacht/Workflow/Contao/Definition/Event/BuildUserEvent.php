<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow\Contao\Definition\Event;


use Netzmacht\Workflow\Security\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CreateUserEvent is dispatched when the user instance is created.
 *
 * @package Netzmacht\Workflow\Factory\Event
 */
class BuildUserEvent extends Event
{
    const NAME = 'workflow.boot.build-user';

    /**
     * The security user.
     *
     * @var User
     */
    private $user;

    /**
     * Construct.
     *
     * @param User $user User instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

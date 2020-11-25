<?php

namespace App\EventSubscriber;

use App\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserSubscriber implements EventSubscriberInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->encodePassword($args->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->encodePassword($args->getEntity());
    }

    private function encodePassword($entity)
    {
        if (!$entity instanceof AdminUser){return;}

        if(!empty($entity->getPlainPassword()) || !is_null($entity->getPlainPassword()))
        {
            $encoded = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encoded);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }
}
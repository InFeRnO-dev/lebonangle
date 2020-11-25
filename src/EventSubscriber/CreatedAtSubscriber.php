<?php


namespace App\EventSubscriber;


use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use DateTime;

class CreatedAtSubscriber implements EventSubscriberInterface
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if(!$entity instanceof Advert && !$entity instanceof Picture){return;}
        $entity->setCreatedAt(new DateTime());
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }
}
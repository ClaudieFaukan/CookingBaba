<?php

namespace App\EventSubscriber;

use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly \Symfony\Component\Mailer\MailerInterface $mailer, private \Doctrine\ORM\EntityManagerInterface $em)
    {
    }


    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
 
        $data = $event->getContactData();

        $email = (new TemplatedEmail())
                ->from($data->getEmail())
                ->to($data->getService())
                ->subject($data->getSubject())
                ->htmlTemplate('emails/contact.html.twig')->context(['data' => $data]);
        $this->mailer->send($email);

        $this->em->persist($data);
        $this->em->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
        ];
    }
}

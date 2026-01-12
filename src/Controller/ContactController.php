<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index', methods: ['GET', 'POST'])]
    public function index(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {

            $eventDispatcher->dispatch(new \App\Event\ContactRequestEvent($data));

            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while sending your message. Please try again later.');
                return $this->redirectToRoute('contact.index');
            }
            
            $this->addFlash('success', 'Your message has been sent successfully!');
            return $this->redirectToRoute('contact.index');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form,
        ]);
    }
}

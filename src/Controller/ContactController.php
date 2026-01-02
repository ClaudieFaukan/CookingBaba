<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index', methods: ['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $email = (new TemplatedEmail())
                    ->from($data->getEmail())
                    ->to($data->getService())
                    ->subject($data->getSubject())
                    ->htmlTemplate('emails/contact.html.twig')->context(['data' => $data]);

                $mailer->send($email);
                $em->persist($data);
                $em->flush();

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

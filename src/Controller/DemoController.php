<?php

namespace App\Controller;

use App\Message\DemoMessage;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class DemoController extends AbstractController
{
    #[Route('/demo', name: 'demo')]
    public function index(): Response
    {
        return $this->render('demo/index.html.twig');
    }

    /**
     * Session
     */
    #[Route('/demo/session', name: 'demo_session', methods:['POST'])]
    #[IsCsrfTokenValid('demo_session', tokenKey: '_csrf_token')]
    public function session(
        Request $request,
        CacheInterface $cache,
        ): Response
    {
        $session = $request->getSession();
        $session->set('couleur', 'rouge');

        $value = $cache->get('statistics', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return ['resultat' => 73];
        });

        return $this->redirectToRoute('demo_session_success');
    }

    #[Route('/demo/session_success', name: 'demo_session_success')]
    public function session_success(
        Request $request,
        ): Response
    {
        $session = $request->getSession();
        return $this->render('demo/session.html.twig', [ 'couleur' => $session->get('couleur')]);
    }

    /**
     * Monolog
     */
    #[Route('/demo/logger', name: 'demo_logger', methods:['POST'])]
    #[IsCsrfTokenValid('demo_logger', tokenKey: '_csrf_token')]
    public function logger(
        LoggerInterface $logger,
    ): Response
    {
        $logger->error('Message d\'erreur');
        $logger->warning('Message d\'avertissement');
        $logger->info('Message d\'information');
        $logger->critical('Message critique', [
            'cause' => 'inconnue'
        ]);

        return $this->redirectToRoute('demo_logger_success');
    }

    #[Route('/demo/logger_success', name: 'demo_logger_success')]
    public function logger_success(): Response
    {
        return $this->render('demo/logger.html.twig');
    }

    /**
     * Symfony Messenger
     */
    #[Route('/demo/messenger', name: 'demo_messenger', methods:['POST'])]
    public function messenger(
        MessageBusInterface $bus,
    ): Response
    {
        $bus->dispatch(new DemoMessage('Dans la communication, le plus compliqué n\'est ni le message, ni la technique, mais le récepteur.'));

        return $this->redirectToRoute('demo_messenger_success');
    }

    #[Route('/demo/messenger/success', name: 'demo_messenger_success')]
    public function messenger_success(): Response
    {
        return $this->render('demo/messenger.html.twig');
    }

    /**
     * Symfony Mailer
     */
    #[Route('/demo/mailer', name: 'demo_mailer', methods:['POST'])]
    #[IsCsrfTokenValid('demo_mailer', tokenKey: '_csrf_token')]
    public function mailer(
        LoggerInterface $logger,
        MailerInterface $mailer,
    ): Response
    {
         $email = new TemplatedEmail()
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Test de Symfony Mailer')
            ->text('Message au format texte.')
            ->htmlTemplate('demo/mailer_message.html.twig')
            ->context([
                'couleur' => 'bleu',
            ]);

        $mailer->send($email);

        $logger->info('Message envoyé');

        return $this->redirectToRoute('demo_mailer_success');
    }

    #[Route('/demo/mailer/success', name: 'demo_mailer_success')]
    public function mailer_success(): Response
    {
        return $this->render('demo/mailer.html.twig', [
            'controller_name' => 'DemoController',
        ]);
    }
            
    #[Route('/demo/form', name: 'demo_form')]
    public function form(): Response
    {
        return $this->render('demo/form.html.twig');
    }
}

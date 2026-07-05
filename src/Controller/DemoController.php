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
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class DemoController extends AbstractController
{
    #[Route('/demo', name: 'demo_demo')]
    public function index(): Response
    {
        return $this->render('demo/index.html.twig');
    }

    #[Route('/demo/session', name: 'demo_session')]
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

        return $this->render('dev/index.html.twig');
    }

    /**
     * Monolog
     */
    #[Route('/demo/logger', name: 'demo_logger')]
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

        return $this->render('demo/index.html.twig');
    }

    /**
     * Symfony Messenger
     */
    #[Route('/demo/messenger', name: 'demo_messenger')]
    public function messenger(
        MessageBusInterface $bus,
    ): Response
    {
        $bus->dispatch(new DemoMessage('Dans la communication, le plus compliqué n\'est ni le message, ni la technique, mais le récepteur.'));

        return $this->render('demo/messenger.html.twig');
    }

    #[Route('/demo/mailer', name: 'demo_mailer')]
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

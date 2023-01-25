<?php


namespace App\Notification;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NouveauCompteNotification1
{
    /**
     * @var  MailerInterface
     */
    private $mailer;
    /**
     * @var  Environment
     */
    private $renderer;

    /**
     * @param MailerInterface $mailer
     * @param Environment $renderer
     */
    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
    public function notify()
    {
        // On construit le mail
        $message = (new TemplatedEmail())
            // Expéditeur
            ->from('pegasustravels10@gmail.com')
            // Destinataire
            ->to('pegasustravels10@gmail.com')
            // Corps du message (créé avec twig)
            ->subject('votre repose a ete ajouter et envoyer a votre client ')
            ->htmlTemplate('reponsereclamation/ajout_reclamation.html.twig');
        // On envoie le mail
        $this->mailer->send($message);
    }



}
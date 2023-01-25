<?php

namespace App\Controller;

use App\Form\SendType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendController extends AbstractController
{
    /**
     * @Route("/send", name="app_send")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this -> createForm(SendType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $send = $form->getData();
            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Users Complaints'))
                ->setFrom($send['email'])
                ->setTo('javafxpegasus@gmail.com')
                ->setBody(
                    $this->renderView(
                        'email/send.html.twig', compact('send')
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('message','le message a bien été envoyé');
            return $this->redirectToRoute('app_login');


        }
        return $this->render('send/index.html.twig', [
            'sendFrom' =>$form->createView(),
        ]);
    }
}

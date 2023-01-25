<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponsereclamation;
use App\Form\Reclamation1Type;
use App\Form\ReponsereclamationType;
use App\Notification\NouveauCompteNotification1;
use App\Repository\ReclamationBackRepository;
use App\Repository\ReclamationRepository;
use App\Repository\ReponsereclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Normalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/reponsereclamation")
 */
class ReponsereclamationController extends AbstractController
{

    /**
     * @var NouveauCompteNotification1
     */
    private $notify_creation;

    public function __construct(NouveauCompteNotification1 $notify_creation)
    {
        $this->notify_creation = $notify_creation;
    }
    /**
     * @Route("/", name="app_reponsereclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,ReponsereclamationRepository $repository): Response
    {

        $reponsereclamations = $entityManager
            ->getRepository(Reponsereclamation::class)
            ->findAll();

        return $this->render('reponsereclamation/index.html.twig', [
            'reponsereclamations' => $reponsereclamations,
        ]);
    }

    /**
     * @Route("/new", name="app_reponsereclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $reponsereclamation = new Reponsereclamation();
        $form = $this->createForm(ReponsereclamationType::class, $reponsereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form['numero']->getData();
            $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);


            $email = (new  TemplatedEmail())
                ->from('pegasustravels10@gmail.com')
                // On attribue le destinataire
                ->to($reclamation->getEmail())
                // On crée le texte avec la vue
                ->htmlTemplate('reclamation/mails.html.twig')
                ->subject('PegasusTravels!')
                ->context(['comm'=>$form["reponse"]->getData()]);
            $mailer->send($email);
            $entityManager->persist($reponsereclamation);
            $entityManager->flush();
            $this->notify_creation->notify();
            return $this->redirectToRoute('app_reponsereclamation_index', [
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponsereclamation/new.html.twig', [

            'reponsereclamation' => $reponsereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idrep}", name="app_reponsereclamation_show", methods={"GET"})
     */
    public function show(Reponsereclamation $reponsereclamation): Response
    {
        return $this->render('reponsereclamation/show.html.twig', [
            'reponsereclamation' => $reponsereclamation,
        ]);
    }

    /**
     * @Route("/{idrep}/edit", name="app_reponsereclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponsereclamation $reponsereclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponsereclamationType::class, $reponsereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponsereclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponsereclamation/edit.html.twig', [
            'reponsereclamation' => $reponsereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idrep}", name="app_reponsereclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponsereclamation $reponsereclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponsereclamation->getIdrep(), $request->request->get('_token'))) {
            $entityManager->remove($reponsereclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponsereclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("statistiques",name="statistiquesRec")
     * @param ReclamationRepository $repository
     * @return Response
     *
     */

    public function statistiques(ReclamationRepository $reclamationRepository): Response
    {
        $nbrs[] = array();

        $e1 = $reclamationRepository->find_Nb_Rec_Par_Status("Voyage");
        dump($e1);
        $nbrs[] = $e1[0][1];


        $e2 = $reclamationRepository->find_Nb_Rec_Par_Status("Evenement");
        dump($e2);
        $nbrs[] = $e2[0][1];
        $e3 = $reclamationRepository->find_Nb_Rec_Par_Status("Hotel");
        dump($e3);
        $nbrs[] = $e3[0][1];

        /*
                $e3=$activiteRepository->find_Nb_Rec_Par_Status("Diffence");
                dump($e3);
                $nbrs[]=$e3[0][1];
        */

        dump($nbrs);
        reset($nbrs);
        dump(reset($nbrs));

        $key = key($nbrs);
        dump($key);
        dump($nbrs[$key]);

        unset($nbrs[$key]);

         $nbrss = array_values($nbrs);
        dump(json_encode($nbrss));

        return $this->render('reponsereclamation/statistique.html.twig', [
            'nbr' => json_encode($nbrss),
        ]);


    }




}

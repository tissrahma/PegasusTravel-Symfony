<?php

namespace App\Controller;
use App\Entity\Reclamation;

use App\Form\Reclamation1Type;
use App\Notification\NouveauCompteNotification;
use App\Form\ReclamationType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

use App\Repository\ReclamationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Component\Form\Form;
/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{


    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();


        $reclamations = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $this->addFlash("success","votre reclamation a ete ajouter");

            $e=$form["email"]->getData();

            $email = (new  TemplatedEmail())
                ->from($e)
                ->to('pegasustravels10@gmail.com')
                ->htmlTemplate('reclamation/mail.html.twig')
                ->subject('🥳 Une nouvelle reclamation est organisé 🥳')

                ->context(['comm'=>$form["commentaire"]->getData(),'typereclamation'=>$form["typereclamation"]->getData()]);


            $mailer->send($email);
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{numero}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{numero}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{numero}", name="app_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getNumero(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/r/search_event", name="search_event")
     */

    public function search_event(Request $request,NormalizerInterface $Normalizer ): Response
    {
        $repository = $this->getDoctrine()->getRepository(Reclamation::class);
        $requestString = $request->get('searchValue');
        $reclamation = $repository->findType($requestString);
        $jsoncontentc = $Normalizer->normalize($reclamation, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        //   dump($jsonc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }

    }


    /**
     * @Route("filter", name="filter")
     */
    public function Filter( ReclamationRepository $repo ,Request $request ,PaginatorInterface $paginator) : Response
    {
        $typereclamation= $_GET['typereclamation'];
        $data = $repo->Filter( $typereclamation);
        $reclamation = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation,
        ]);

}


}

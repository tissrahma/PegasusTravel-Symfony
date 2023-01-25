<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\ReponsereclamationType;
use App\Repository\ReclamationBackRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf ;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Normalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/reclamation/back")
 */
class ReclamationBackController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_back_index", methods={"GET"})
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

        return $this->render('reclamation_back/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_back_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation_back/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{numero}", name="app_reclamation_back_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation_back/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{numero}", name="app_reclamation_back_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getNumero(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("imp", name="impr")
     */
    public function imprimer(ReclamationRepository  $repository ,EntityManagerInterface $entityManager): Response

    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $evenements = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation_back/pdf.html.twig', [
            'reclamations' => $evenements,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste  Reclamation.pdf", [
            "Attachment" => true

        ]);
        return 0;
    }

    /**
     * @param ReclamationRepository $repository
     * @return Response
     * @Route ("tri",name="tri")
     */
    function OrderByPrice(ReclamationRepository  $repository,Request $request,PaginatorInterface $paginator){
        $donnees=$repository->OrderByPrice();
        $reclamations = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render("reclamation_back/index.html.twig",['reclamations'=>$reclamations]);

    }

    /**
     * @Route("search_concours", name="search")
     */
    public function search(Request $request,ReclamationRepository $concoursRepository): Response
    {
        $nom = $_GET['nom'];
        return $this->render('reclamation_back/index.html.twig', [
            'reclamations' => $concoursRepository->createQueryBuilder('u')->select('u')->where("u.nom  = '".$nom."' ")->getQuery()->getResult(),

            'notifs' => $this->getDoctrine()
                ->getRepository(Reclamation::class)
                ->findAll(),

        ]);
    }

    /**
     * @Route("/r/search_back", name="search_back",methods={"GET"})
     */

    public function search_back(Request $request,NormalizerInterface $Normalizer,ReclamationBackRepository $reclamationBackRepository ): Response
    {

        $requestString = $request->get('searchValue');
        $requestString3 = $request->get('orderid');
        $Reclamation = $reclamationBackRepository->findevent($requestString, $requestString3);
        $jsonContent = $Normalizer->normalize($Reclamation, 'json', ['groups' => 'posts:read']);
        //  dump($jsoncontentc);
        $jsonc = json_encode($jsonContent);
        //   dump($jsonc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }


    /**
     * @Route("filte", name="filte")
     */
    public function Filter( ReclamationBackRepository $repo ,Request $request ,PaginatorInterface $paginator) : Response
    {
        $datereclamation= $_GET['datereclamation'];
        $data = $repo->Filte( $datereclamation);
        $reclamation = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('reclamation_back/index.html.twig', [
            'reclamations' => $reclamation,
        ]);

    }






    /**
     * @Route("/r/search_back1", name="search_back1",methods={"GET"})
     */

    public function search_back1(Request $request,ReclamationBackRepository $reclamationBackRepository ): Response
    {

        $requestString = $request->get('searchValue');
        $Reclamations = $reclamationBackRepository->FindRecWithNAME($requestString);
        $responseArray = [];
        $idx = 0;

        foreach ($Reclamations as $Reclamation) {
            $temp = [

                'nom' => $Reclamation->getNom(),
                'prenom' => $Reclamation->getPrenom(),
                'email' => $Reclamation->getEmail(),
                'commentaire'=>$Reclamation->getCommentaire(),
                'datereclamation' => $Reclamation->getDatereclamation()->format(' Y-m-d'),
                'typereclamation' => $Reclamation->getTypereclamation(),
            ];

            $responseArray[$idx++] = $temp;
        }
        return new JsonResponse($responseArray);
    }





    /**
     * @Route("DOWNtriEQUIPE", name="DOWNtriEQUIPE",options={"expose"=true})
     */
    public function DOWNtriEQUIPE(Request $request,ReclamationBackRepository $repository): JsonResponse
    {

        $UPorDOWN=$request->get('order');
        $Reclamations=$repository->DescReclamationSearch($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Reclamations as $Reclamation){
            $temp = [

                'nom' => $Reclamation->getNom(),
                'prenom' => $Reclamation->getPrenom(),
                'email' => $Reclamation->getEmail(),
                'commentaire'=>$Reclamation->getCommentaire(),
                'datereclamation' => $Reclamation->getDatereclamation()->format(' Y-m-d'),
                'typereclamation' => $Reclamation->getTypereclamation()
            ];

            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }



    /**
     * @Route("UPtriEQUIPE", name="UPtriEQUIPE",options={"expose"=true})
     */
    public function UPtriEQUIPE(Request $request,ReclamationBackRepository $repository): JsonResponse
    {


        $UPorDOWN=$request->get('order');
        $Reclamations=$repository->AscReclamationSearch ($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Reclamations as $Reclamation){
            $temp = [

                'nom' => $Reclamation->getNom(),
                'prenom' => $Reclamation->getPrenom(),
                'email' => $Reclamation->getEmail(),
                'commentaire'=>$Reclamation->getCommentaire(),
                'datereclamation' => $Reclamation->getDatereclamation()->format(' Y-m-d'),
                'typereclamation' => $Reclamation->getTypereclamation(),
            ];
            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }
}
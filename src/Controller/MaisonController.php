<?php


namespace App\Controller;

use App\Entity\Maisonh;
use App\Form\MaisonType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;







class MaisonController extends AbstractController
{
    /**
     * @Route("/BackMaison", name="app_maison")
     */

    public function index(): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
      //  $flashy->primaryDark('Thanks for signing up!', 'http://your-awesome-link.com');

        return $this->redirectToRoute('Recherche',array('m' => $maison));
      //  return $this->render('maison/index.html.twig', array('m' => $maison));



    }



    /**
     * @Route("/FrontMaison", name="app_maisonF")
     */


    public function indexFrontMaison(Request $request, PaginatorInterface $paginator ): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
        $maison = $paginator->paginate(
            $maison, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );
      //  $flashy->success('Bienvenue à la liste des maisons dhote de pegasus travel :) !');

        return $this->render('maisonFront/index.html.twig', [
            'm'=>$maison
        ]);
    }




    /**
     * @Route("/addMaison", name="addMaison")
     */
    public function addMaison(Request $request ): Response
    {
        $maison = new Maisonh();
        $form = $this->createForm(MaisonType::class,$maison);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file = $maison->getImageMaison();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads'),$filename);
            $maison->setImageMaison($filename);
            $em=$this->getDoctrine()->getManager();
            $em->persist($maison);
            $em->flush();

            return $this->redirectToRoute('app_maison');

        }

        return $this->render('maison/ajouterMaison.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/removemaison/{id}", name="supp_maison")
     */
    public function supprimerMaison(Maisonh $maison): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($maison);

        $em->flush();

        return $this->redirectToRoute('app_maison');
      //  $flashy->success('maison supprimé avec succés');

    }


    /**
     * @Route("/imprimmaison", name="imprimmaison")
     */
    public function imprimmaison(): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('maison/imprimmaison.html.twig', [
            'm'=>$maison,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste maison.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/modifMaison/{id}", name="modifMaison")
     */
    public function modifierMaison(Request $request,$id): Response
    {
        $maison= $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->find($id);
        $form = $this->createForm(MaisonType::class,$maison);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file = $maison->getImageMaison();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads'),$filename);
            $maison->setImageMaison($filename);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_maison');
        }
        return $this->render('maison/updateMaison.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/trimaison", name="trimaison")
     */
    public function Tri(Request $request)
    {

        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(

            'SELECT m FROM App\Entity\Maisonh m
            ORDER BY m.nom '

        );

        $maison = $query->getResult();
        return $this->render('maison/index.html.twig',
            array('m' => $maison));

    }
    /**
     * @Route("/trimaisonp", name="trimaisonp")
     */
    public function TriPrix(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(
            'SELECT m FROM App\Entity\Maisonh m
            ORDER BY m.prix '
        );

        $maison = $query->getResult();



        return $this->render('maison/index.html.twig',
            array('m' => $maison));

    }

    /**
     * @Route("/statmaison", name="statmaison")
     */
    public function stat()
    {

        $repository = $this->getDoctrine()->getRepository(Maisonh::class);
        $maison = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1 = 0;
        $pr2 = 0;


        foreach ($maison as $maison) {
            if ($maison->getPrix() >= "1000")  :

                $pr1 += 1;
            else:

                $pr2 += 1;

            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nom'],
                ['maison à prix supérieur à 1000dt', $pr1],
                ['maison d"hote à prix inférieur à 1000dt', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('Prix des maisons');
        $pieChart->getOptions()->setHeight(1000);
        $pieChart->getOptions()->setWidth(1400);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('green');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(30);

       // $flashy->primaryDark('statistique des prix des maisons : !');

        return $this->render('maison/statMaison.html.twig', array('piechart' => $pieChart));
    }

     /**
     *@Route("/rating",name="rating")
     */
    public function note(Request $request)
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();



        return $this->render('maison/rating.html.twig', [
            'm' => $maison,
        ]);
    }
    /**
     *@Route("/mapM",name="mapM")
     */
    public function Map()
    {

        return $this->render('maison/map.html.twig');
    }



    /**
     * @Route("/Recherche", name="Recherche")
     */
   public function rechercheByName(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $maison=$em->getRepository(Maisonh::class)->findAll();
        if($request->isMethod("POST"))
        {
            $prix = $request->get('prix');
            $maison=$em->getRepository(Maisonh::class)->findBy(array('prix'=>$prix));
        }
     //   $flashy->success('Bienvenue à pegasus Travel back office :) !');

        return $this->render('maison/index.html.twig', array('m' => $maison));
    //    return $this->redirectToRoute('Recherche',array('m' => $maison));


    }



    /**
     * @Route("/displayMaisonMobile", name="displayMaisonMobile")
     */
    public function displayMaisonMobile(NormalizerInterface $normalizer): Response
    {
        $maison = $this->getDoctrine()->getRepository(Maisonh::class)->findAll();
        $jsonContent = $normalizer->normalize($maison, 'json', ['groups' => 'post:read']);
        return $this->render('maison/displaym.html.twig',
            ['data' => $jsonContent]);
    }

    /**
     * @Route("/addMaisonMobile", name="addMaisonMobile")
     */
    public function addMaisonMobile(Request $request, NormalizerInterface $normalizer): Response
    {
        $maison = new Maisonh();
        $entityManager = $this->getDoctrine()->getManager();
        $maison->setNom($request->get('nom'));
        $localisation = $request->query->get('localisation');
        $maison->setLocalisation($localisation);
        $description = $request->query->get('description');
        $maison->setDescription($description);
        $prix = $request->query->get('prix');
        $maison->setPrix($prix);
        $imageMaison = $request->query->get('imageMaison');
        $maison->setImageMaison($imageMaison);
        $entityManager->persist($maison);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($maison, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/deleteMaisonMobile", name="deleteMaisonMobile")
     */
    public function deleteMaisonMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->query->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $maison = $entityManager->getRepository(Maisonh::class)->find($id);
        if ($maison != null) {
            $entityManager->remove($maison);
            $entityManager->flush();
            $formatted = $serializer->normalize($maison, 'json', ['groups' => 'post:read']);

            return new Response(json_encode($formatted));

        }

        return new Response(" maison n'existe pas");
    }
    /**
     * @Route("/updateMaisonMobile", name="updateMaisonMobile")
     */
    public function updateMaisonMobile(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $maison = $this->getDoctrine()->getManager()
            ->getRepository(Maisonh::class)
            ->find($request->get("id"));

        $maison->setNom($request->get("nom"));
        $maison->setLocalisation($request->get("localisation"));
        $maison->setDescription($request->get("description"));
        $maison->setPrix($request->get("prix"));
        $maison->setImageMaison($request->get("imageMaison"));


        $em->persist($maison);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($maison);
        return new JsonResponse("maison a été modifiee avec success.");

    }






}
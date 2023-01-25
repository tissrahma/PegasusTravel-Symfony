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






class MaisonController extends AbstractController
{
    /**
     * @Route("/BackMaison", name="app_maison")
     */

    public function index(FlashyNotifier $flashy): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
        $flashy->success('Bienvenue à pegasus Travel back office :) !');

        return $this->redirectToRoute('Recherche',array('m' => $maison));
      //  return $this->render('maison/index.html.twig', array('m' => $maison));



    }



    /**
     * @Route("/FrontMaison", name="app_maisonF")
     */


    public function indexFrontMaison(Request $request, PaginatorInterface $paginator ,FlashyNotifier $flashy): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
        $maison = $paginator->paginate(
            $maison, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );
        $flashy->success('Bienvenue à la liste des maisons dhote de pegasus travel :) !');

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
            $file->move($this->getParameter('uploads_directory'),$filename);
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
    public function supprimerMaison(Maisonh $maison,FlashyNotifier $flashy): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($maison);

        $em->flush();

        return $this->redirectToRoute('app_maison');
        $flashy->success('maison supprimé avec succés');

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
            $file->move($this->getParameter('uploads_directory'),$filename);
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
    public function stat(FlashyNotifier $flashy)
    {

        $repository = $this->getDoctrine()->getRepository(Maisonh::class);
        $maison = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1 = 0;
        $pr2 = 0;


        foreach ($maison as $maison) {
            if ($maison->getPrix() == "550")  :

                $pr1 += 1;
            else:

                $pr2 += 1;

            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nom'],
                ['550', $pr1],
                ['340', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('Prix des maisons');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('green');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $flashy->primaryDark('statistique des prix des maisons !');

        return $this->render('maison/statMaison.html.twig', array('piechart' => $pieChart));
    }



    /**
     * @Route("/Recherche", name="Recherche")
     */
   public function rechercheByName(Request $request,FlashyNotifier $flashy)
    {
        $em=$this->getDoctrine()->getManager();
        $maison=$em->getRepository(Maisonh::class)->findAll();
        if($request->isMethod("POST"))
        {
            $nom = $request->get('nom');
            $maison=$em->getRepository(Maisonh::class)->findBy(array('nom'=>$nom));
        }
        $flashy->success('Bienvenue à pegasus Travel back office :) !');

        return $this->render('maison/index.html.twig', array('m' => $maison));
    //    return $this->redirectToRoute('Recherche',array('m' => $maison));


    }

}
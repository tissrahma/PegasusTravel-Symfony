<?php

namespace App\Controller;

use App\Entity\Reservationv;
use App\Form\ReservationvType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\DocBlock\Serializer;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/reservationv")
 */
class ReservationvController extends AbstractController
{

    /**
     * @Route("/", name="app_reservationv_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservationvs = $entityManager
            ->getRepository(Reservationv::class)
            ->findAll();

        return $this->render('reservationv/index.html.twig', [
            'reservationvs' => $reservationvs,
        ]);
    }
    /**
     * @Route("/Adminv", name="app_reservationv_affiche", methods={"GET"})
     */
    public function indexaffiche(EntityManagerInterface $entityManager): Response
    {
        $reservationvs = $entityManager
            ->getRepository(Reservationv::class)
            ->findAll();

        return $this->render('reservationv/afficheReservationv.html.twig', [
            'reservationvs' => $reservationvs,
        ]);
    }

    /**
     * @Route("/imprimresv", name="imprimresv")
     */
    public function imprimresv(EntityManagerInterface $entityManager): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $reservationvs = $entityManager
            ->getRepository(Reservationv::class)
            ->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservationv/imprimreservationv.html.twig', [
            'reservationvs' => $reservationvs,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste Voyage.pdf", [
            "Attachment" => true
        ]);
    }



    /**
     * @Route("/new", name="app_reservationv", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationv = new Reservationv();
        $form = $this->createForm(ReservationvType::class, $reservationv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationv);
            $entityManager->flush();
            $this->addFlash('success', 'votre réservation est faite avec succés!');


            return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationv/new.html.twig', [
            'reservationv' => $reservationv,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/new/{id}", name="app_reservationv_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationv = new Reservationv();
        $form = $this->createForm(ReservationvType::class, $reservationv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationv);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationv/new.html.twig', [
            'reservationv' => $reservationv,
            'form' => $form->createView(),
        ]);
    }  /**
 * @Route("/succes", name="succes")
 */
    public function succes(): Response
    {
        return $this->render('reservationv/success.html.twig');
    }

    /**
     * @Route("/{idr}", name="app_reservationv_show", methods={"GET"})
     */
    public function show(Reservationv $reservationv): Response
    {
        return $this->render('reservationv/show.html.twig', [
            'reservationv' => $reservationv,
        ]);
    }

    /**
     * @Route("/{idr}/edit", name="app_reservationv_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservationv $reservationv, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationvType::class, $reservationv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationv/edit.html.twig', [
            'reservationv' => $reservationv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_reservationv_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservationv $reservationv, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationv->getIdr(), $request->request->get('_token'))) {
            $entityManager->remove($reservationv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/remove/{idr}", name="app_reservationv_deletee")
     */
    public function deletereservation(Reservationv $reservationvs): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($reservationvs);
        $em->flush();

        return $this->redirectToRoute('app_reservationv_affiche');
    }






}

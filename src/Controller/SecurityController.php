<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ForgotpasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/Users", name="Users")
     */
    public function index(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $items = $repository->findAll();

        return $this->render('admin/users.html.twig',['users'=> $items]
        );



    }


    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $items = $repository->findAll();

        return $this->render('/baseFront.html.twig');

    }

    ///**
     //* @Route("/myaccount", name="account")
    // */
  // public function myacc(): Response
   //{

        //$repository = $this->getDoctrine()->getRepository(Users::class);

        //$items = $this->get('security.token_storage')->getToken()->getUser();

        //return $this->render('user/myacc.html.twig',['user'=> $items]
       // );


    //}



    /**
    * @Route("/edit/{id}", name="edit_user")
    * @ParamConverter("user", class="App:Users")
    */
    public function edituser(Request $request, $user)
    {
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $data = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->findOneBy(array('id' => $data->getId()));
        if ($form->isSubmitted()) {

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('Users');
        }

        return $this->render('admin/edit.html.twig', [
            'format' => $form->createView(),
        ]);
    }



    /**
     * @Route("delete/{id}", name="delete_user")
     */
    public function delete(Users $u)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($u);
        $em->flush();
        return $this->redirectToRoute('Users');
    }
    /**
     * @Route("change/{id}", name="change_state")
     */
    public function ban(Users $u)
    {
        $em = $this->getDoctrine()->getManager();
        $u->setRoles(['blocked']);
        $em->flush();
        return $this->redirectToRoute('Users');
    }
    /**
     * @Route("unban/{id}", name="unban")
     */
    public function unban(Users $u)
    {
        $em = $this->getDoctrine()->getManager();
        $u->setRoles(['Client']);
        $em->flush();
        return $this->redirectToRoute('Users');
    }

    /**
     * @Route("/ajouteruser", name="add_user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function add(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        $l = new Users();
        $form = $this->createForm(UserType::class, $l);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $l->setPassword(
                $userPasswordEncoder->encodePassword(
                    $l,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($l);
            $entityManager->flush();
            return $this->redirectToRoute('Users');
        }
        return $this->render('admin/adduser.html.twig', array(
            'format' => $form->createView(),
        ));
    }

    /**
     * @Route("/forgot", name="forgot")
     */
    public function forgotpass(Request $request, UserRepository $userRepository,\Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
       $form = $this->createForm(ForgotpasswordType::class);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $donnees = $form->getData();

            $user =$userRepository->findOneBy(['email'=>$donnees]);

            if(!$user){
                $this->addFlash('danger', 'cette adresse n\'existe pas');

            }

            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();

            }
            catch(\Exception $exception){
                $this->addFlash('warning', 'erreur:'.$exception->getMessage());


            }

            $url = $this->generateUrl('app_reset_password', array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);
            //mail bundle

            $message =(new \Swift_Message('mot de passe oublier'))
                ->setFrom('javafxpegasus@gmail.com')
                ->setTo($user->getEmail())
                ->setBody("<p>Bonjour</p> une demande de réinitialisation de mot de passe a été effectuer . veuillez cliquer le lien suivant:".$url,
                    "text/html");
            //send mail

            $mailer->send($message);
            $this->addFlash('message', 'E-mail de réinitialisation de mot de passe a été envoyer:');
            return $this->redirectToRoute('app_login');




        }

        return $this->render("security/forgotpassword.html.twig",['form'=>$form->createView()]);

    }

    /**
     * @Route("/resetpassword", name="app_reset_password")
     */
    public function resetpassword(): void
    {

    }


}

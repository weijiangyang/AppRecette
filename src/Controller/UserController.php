<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edit/{id}', name: 'user_edit')]
    #[Security("is_granted('ROLE_USER') and user === chosenUser")]
    public function index(User $chosenUser,Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserType::class,$chosenUser);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($chosenUser,$form->getData()->getPlainPassword())){
                /**
                 * @var User
                 */
                $chosenUser = $form->getData();
                $em->persist($chosenUser);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Les informations de votre compte a bien été modifié!'
                );
                return $this->redirectToRoute('user_show',[
                    'id'=> $chosenUser->getId()
                ]);
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect!'
                );
                return $this->redirectToRoute('user_edit',[
                    'id'=> $chosenUser->getId()
                ]);
            }
         
        }
        return $this->render('pages/user/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('utilisateur/show/{id}', name:'user_show')]
    #[IsGranted('ROLE_USER')]
    public function show(User $user){

        return $this->render('pages/user/show.html.twig',[
            'user' => $user
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'user_edit_password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user == chosenUser")]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em, User $chosenUser): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        // 
        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($hasher->isPasswordValid($chosenUser, $form->getData()['oldPlainPassword'])) {
                if(
                    $form->getData()['plainPassword'] === $form->getData()['confirmation'])
                  {
                    if (preg_match("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^", $form->getData()['plainPassword'])){
                        $chosenUser->setPlainPassword($form->getData()['plainPassword']);
                        $chosenUser->setPassword($hasher->hashPassword($chosenUser, $chosenUser->getPlainPassword()));
                        $em->persist($chosenUser);
                        $em->flush();

                        $this->addFlash(
                            'success',
                            'Votre mot de passe a bien été modifié'
                        );


                        return $this->redirectToRoute('user_show', [
                            'id' => $chosenUser->getId()
                        ]);
                    }else{
                        $this->addFlash(
                            'warning',
                            'Le mot de passe doit contenir au moins 8 caractères, avec au moins une lettre et un chiffre'
                        );
                    }

                  } 
                
                   
                else{
                    $this->addFlash(
                        'warning',
                        'Les mots de passe ne correspondent pas!'
                    );
                }
                
               
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe actuel n\'est pas correct'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

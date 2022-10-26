<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edit/{id}', name: 'user_edit')]
    public function index(User $user,Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $hasher): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security_login');
        }

        if($this->getUser() != $user){
            return $this->redirectToRoute('recipe_index');
        }

        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user,$form->getData()->getPlainPassword())){
                /**
                 * @var User
                 */
                $user = $form->getData();
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Les informations de votre compte a bien été modifié!'
                );
                return $this->redirectToRoute('app_index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect!'
                );
                return $this->redirectToRoute('user_edit',[
                    'id'=> $user->getId()
                ]);
            }
         
        }
        return $this->render('pages/user/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user == chosenUser")]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em, User $chosenUser): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        // 
        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($hasher->isPasswordValid($chosenUser, $form->getData()['oldPlainPassword'])) {
                if($form->getData()['plainPassword'] === $form->getData()['confirmation']){
                    $chosenUser->setPlainPassword($form->getData()['plainPassword']);
                    $chosenUser->setPassword($hasher->hashPassword($chosenUser,$chosenUser->getPlainPassword()));
                    $em->persist($chosenUser);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Votre mot de passe a bien été modifié'
                    );


                    return $this->redirectToRoute('app_index');
                }else{
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

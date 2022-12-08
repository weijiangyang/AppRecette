<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommentController extends AbstractController
{
    /**
     * Pour ajouter un commentaire
     *      
     * @param UserRepository $userRepository
     * @param RecipeRepository $recipeRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/ajax/comments', name: 'comment_add')]
    #[IsGranted('ROLE_USER')]
    public function add(UserRepository $userRepository, RecipeRepository $recipeRepository, Request $request, EntityManagerInterface $em): Response
    {
        $commentData = $request->request->all('comment');
    
        if (!$this->isCsrfTokenValid('comment_add', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
        }

        $recipe = $recipeRepository->find($commentData['recipe']);
        if (!$recipe) {
            return $this->json([
                'code' => 'ARTICLE_NO_EXIST'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 'USER_NOT_AUTHENTICATED_FULLY'
            ], Response::HTTP_BAD_REQUEST);
        }
        $comment = new Comment($recipe);
        $comment->setContent($commentData['content'])
        ->setUser($user);
            

        $em->persist($comment);
        $em->flush();

        $html = $this->renderView('pages/comment/show.html.twig', [
            'comment' => $comment
        ]);

        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html,
            'numberOfComments' => count($recipe->getComments())
        ], 200);
    }

   
    /**
     * Pour supprimer un commentaire
     *
     * @param Comment $comment
     * @param EntityManagerInterface $em
     * @return void
     */
    #[Route('/comment/supprimer/{id}', name: 'comment_delete', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and  user === comment.getUser()")]
    public function delete(Comment $comment, EntityManagerInterface $em){
       
        $em->remove($comment);
        $em->flush();
        $this->addFlash(
            'success',
            'Votre commentaire a été bien supprimé avec success!'
        );

        return $this->redirectToRoute('recipe_show',[
            'slug'=> $comment->getRecipe()->getSlug()
        ]);
    }
}

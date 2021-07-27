<?php

namespace App\Controller\api\v1;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\FindIdByJWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/v1/comment/", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("add", name="add", methods={"POST"})
     */
    public function add(Request $request, FindIdByJWT $findIdByJWT): Response
    {
        $jwt = $request->headers->get('authorization');
        $userId = $findIdByJWT->find($jwt);
        $userId = json_encode($userId);
        $json = $request->getContent();
        $commentArray = json_decode($json, true);
        $commentArray["user"] = $userId;
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);
        $form->submit($commentArray);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->json($comment);
        } else {
            return $this->json([
                'errors' => (string) $form->getErrors(true, false),
            ], 400);
        }
    }

    /**
     * @Route("delete/{id}", name="delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Comment $comment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return new Response;
    }
}

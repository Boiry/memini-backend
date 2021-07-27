<?php

namespace App\Controller\api\v1;

use App\Entity\Memini;
use App\Form\MeminiType;
use App\Repository\CommentRepository;
use App\Repository\MeminiRepository;
use App\Service\FindIdByJWT;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api/v1/memini/", name="memini_")
 */
class MeminiController extends AbstractController
{
    /**
     * @Route("browse", name="browse", methods={"GET"})
     */
    public function browse(
        Request $request,
        FindIdByJWT $findIdByJWT,
        MeminiRepository $meminiRepository,
        CommentRepository $commentRepository
        ): Response
    {
        $jwt = $request->headers->get('authorization');
        $userId = $findIdByJWT->find($jwt);
        $meminis = $meminiRepository->findAllPersonalMeminis($userId);
        foreach ($meminis as $memini) {
            $comment = $commentRepository->findByMemini($memini->getId());
            $memini->setComments($comment);
        }
        return $this->json($meminis);
    }

    /**
     * @Route("read/{id}", name="read", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function read(Memini $memini, CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findByMemini($memini->getId());
        $memini->setComments($comment);
        return $this->json($memini);
    }

    /**
     * @Route("add", name="add", methods={"POST"})
     */
    public function add(Request $request, FindIdByJWT $findIdByJWT, ImageUploader $imageUploader): Response
    {
        $jwt = $request->headers->get('authorization');
        $userId = $findIdByJWT->find($jwt);
        $userId = json_encode($userId);
        $json = $request->getContent();
        $meminiArray = json_decode($json, true);
        $meminiArray["user"] = $userId;

        $publicStatus = $meminiArray["publicStatus"];
        $meminiArray["public"] = $publicStatus;
        unset($meminiArray["publicStatus"]);

        $sendAtInput = $meminiArray["sendAt"];
        $now = new \DateTime("now");
        if ($sendAtInput == 1) {
            $sendAtInput = rand(2, 6);
        }
            switch ($sendAtInput) {
                case 2: // 1 mois
                    $sendAt = $now->add(new \DateInterval('P1M'));
                    break;
                case 3: // 6 mois
                    $sendAt = $now->add(new \DateInterval('P6M'));
                    break;
                case 4: // 1 an
                    $sendAt = $now->add(new \DateInterval('P1Y'));
                    break;
                case 5: // 5 ans
                    $sendAt = $now->add(new \DateInterval('P5Y'));
                    break;
                case 6: // demain
                    $sendAt = $now->add(new \DateInterval('P1D'));
                    break;
            }
        $meminiArray["sendAt"] = $sendAt->format('Y-m-d H:i:s');

        if (!empty($meminiArray["picture"])) {
            $meminiArray["picture"] = $imageUploader->upload($meminiArray["picture"], "picture");
        }

        $memini = new Memini();
        $form = $this->createForm(MeminiType::class, $memini, ['csrf_protection' => false]);
        $form->submit($meminiArray);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($memini);
            $em->flush();
            return $this->json($memini);
        } else {
            return $this->json([
                'errors' => (string) $form->getErrors(true, false),
            ], 400);
        }
    }

    /**
     * @Route("delete/{id}", name="delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Memini $memini): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($memini);
        $em->flush();
        return new Response;
    }
}

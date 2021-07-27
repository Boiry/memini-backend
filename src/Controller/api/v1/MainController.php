<?php

namespace App\Controller\api\v1;

use App\Repository\CommentRepository;
use App\Repository\MeminiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="home", methods={"GET"})
     */
    public function home(MeminiRepository $meminiRepository, CommentRepository $commentRepository): Response
    {
        $meminis = $meminiRepository->findForHomepage();
        foreach ($meminis as $memini) {
            $comment = $commentRepository->findByMemini($memini->getId());
            $memini->setComments($comment);
        }
        return $this->json($meminis);
    }
}
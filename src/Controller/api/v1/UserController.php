<?php

namespace App\Controller\api\v1;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FindIdByJWT;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("api/v1/user/browse", name="browse")
     */
    public function browse(UserRepository $UserRepository): Response
    {
        $user = $UserRepository->findAll();
        return $this->json($user);
    }

    /**
     * @Route("api/v1/user/read", name="read", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function read(Request $request, UserRepository $userRepository, FindIdByJWT $findIdByJWT): Response
    {
        $jwt = $request->headers->get('authorization');
        $userId = $findIdByJWT->find($jwt);
        $user = $userRepository->find($userId);
        return $this->json($user);
    }

    /**
     * @Route("api/v1/user/edit", name="edit", methods={"PATCH"})
    */
    public function edit(
        Request $request,
        UserRepository $userRepository,
        FindIdByJWT $findIdByJWT,
        ImageUploader $imageUploader,
        UserPasswordEncoderInterface $encoder
        ): Response
    {
        $jwt = $request->headers->get('authorization');
        $userId = $findIdByJWT->find($jwt);
        $user = $userRepository->find($userId);

        $json = $request->getContent();
        $userArray = json_decode($json, true);

        if ($userArray === null) {
            return $this->json($user);
        }
        if (empty($userArray["name"])) {
            $userArray["name"] = $user->getName();
        }
        if (empty($userArray["email"])) {
            $userArray["email"] = $user->getEmail();
        }
        if (!empty($userArray["avatar"])) {
            $userArray["avatar"] = $imageUploader->upload($userArray["avatar"], "avatar");
        }

        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);
        $form->submit($userArray);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setUpdatedAt(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->json($user);
        }
    }

    /**
     * @Route("user/add", name="add", methods={"POST"})
    */
    public function add(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $json = $request->getContent();
        $userArray = json_decode($json, true);
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);
        $form->submit($userArray);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->json($user);
        } else {
            return $this->json([
                'errors' => (string) $form->getErrors(true, false),
            ], 400);
        }
    }
 
}
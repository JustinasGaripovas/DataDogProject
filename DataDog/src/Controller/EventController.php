<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Form\EventCommentType;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class   EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index")
     */
    public function index(EventRepository $repository, Request $request)
    {
        $events = $repository->findNewest();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/newevent", name="event_new")
     */
    public function newEvent(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setCreatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();

            if($event->getImage()){
            $file = $event->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $event->setImage($fileName);}

            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * Kai padarai {id} slug, ir i paduodamu funkcijai parametrus irasai Event $event tada jis automatiskai suranda toki event su tokiu id
     * @Route("/event/{id}", name="event_show")
     */
    public function showEvent(Request $request, Event $event)
    {
        dump($event);

        /*
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setCreatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_index');
        }*/

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/event/{id}/edit", name="event_edit")
     */
    public function editEvent(Request $request, Event $event)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $oldImage = $event->getImage();
        if($event->getImage()) {

            $event->setImage(
                new File($this->getParameter('images_directory') . '/' . $event->getImage())
            );
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            //------------------
            if($event->getImage()){
            $file = $event->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $event->setImage($fileName);
            }
            else{
                $event->setImage($oldImage);
            }

            //--------------------

            $em->flush();
            return $this->redirectToRoute('event_index');
        }
        //$form->handleRequest($request);
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
    /**
     * @Route("/event/{id}/delete", name="event_delete")
     */
    public function deleteEvent(Request $request, Event $event)
    {

        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('event_index');
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/event/{id}/comment", name="event_comment")
     */
    public function newComment(Request $request, Event $event, TokenStorageInterface $tokenStorage)
    {
        $comment = new Comment();
        $form = $this->createForm(EventCommentType::class, $comment);
        $form->handleRequest($request);
        $token = $tokenStorage->getToken();
        $user = $token->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setDate(new \DateTime('now'));
            $comment->setAuthor($user->getUsername());
            $comment->setEvent($event);
            $em = $this->getDoctrine()->getManager();

            $em->persist($comment);
            $em->flush();

            $event->addComment($comment);
            $em->flush();

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }


}

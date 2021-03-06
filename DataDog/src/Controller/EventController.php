<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\User;
use App\Form\EventCommentType;
use App\Form\EventType;
use App\Form\FilterEventType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class   EventController extends AbstractController
{
    const MAX_COMMENT_LENGTH = 500;

    private $mailer;
    private $categoryRepository;

    public function __construct(\Swift_Mailer $mailer,CategoryRepository $categoryRepository)
    {
        $this->mailer = $mailer;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="event_index")
     */
    public function index(EventRepository $repository, Request $request)
    {
        $form = $this->createForm(FilterEventType::class);
        $requestParameters = $request->request->all();
        $events = $repository->findNewest($requestParameters);

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'filterForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function  about(Request $request)
    {
        return $this->render('about/index.html.twig', [
        ]);
    }

    /**
     * @Route("/newevent", name="event_new")
     */
    public function newEvent(Request $request,  \Swift_Mailer $mailer)
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
                } catch (FileException $e) {}

                $event->setImage($fileName);}

            $em->persist($event);
            $em->flush();

            $this->notifySubscribers($event->getEventCategories(),$event->getId(),$mailer);


            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    private function notifySubscribers($categories,$eventId, \Swift_Mailer $mailer)
    {

        foreach ($categories as $category)
        {
            foreach ($category->getUsers() as $user)
            {
               $message = (new \Swift_Message('New event !'))
                    ->setFrom('datadog.ktu@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'email/category_update_email.html.twig',
                            ['category' => $category->getName(), 'eventId' => (int)$eventId]

                        ),
                        'text/html'
                    )
                ;

                $this->mailer->send($message);
            }
        }
    }

    /**
     * Kai padarai {id} slug, ir i paduodamu funkcijai parametrus irasai Event $event tada jis automatiskai suranda toki event su tokiu id
     * @Route("/event/{id}", name="event_show")
     */
    public function showEvent(Request $request, Event $event)
    {
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
            if($event->getImage()){
            $file = $event->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {}

            $event->setImage($fileName);
            }
            else{
                $event->setImage($oldImage);
            }

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
     * @Route("/event/add/comment", name="event_new_comment")
     */
    public function newComment(Request $request, TokenStorageInterface $tokenStorage, EventRepository $eventRepository)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')==false)
        {
            return new JsonResponse(["status"=>"03","message"=>"Reikia būti prisijungusiam."]);
        }

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $eventId = $request->request->get('eventId');
            $event = $eventRepository->findById($eventId);
            $text = $request->request->get('text');
        } else {
            return new JsonResponse(["status"=>"04","message"=>"Ajax only."]);
        }

        if(empty($text)) {
            return new JsonResponse(["status"=>"01","message"=>"Nėra žinutės."]);
        }elseif(strlen($text) >= self::MAX_COMMENT_LENGTH){
            return new JsonResponse(["status"=>"01","message"=>"Žinutė per ilga. (max ". self::MAX_COMMENT_LENGTH ." simbolių)"]);
        }
        if(empty($event)) {
            return new JsonResponse(["status"=>"02","message"=>"Nėra tokio renginio."]);
        }

        $comment = new Comment();
        $token = $tokenStorage->getToken();
        $user = $token->getUser();

        $comment->setDate(new \DateTime('now'));
        $comment->setAuthor($user->getUsername());
        $comment->setText($text);
        $comment->setEvent($event);
        $em = $this->getDoctrine()->getManager();

        $em->persist($comment);
        $em->flush();

        $event->addComment($comment);
        $em->flush();

        return new JsonResponse(["status"=>"00","message"=>"Sėkmingai išsaugotas komentaras."]);

    }

    /**
     * @Route("/{id}", name="comment_delete", methods="DELETE")
     */
    public function deleteComment(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $event_id = $request->query->get('event_id');

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('event_show', ['id' => $event_id]);
    }



}

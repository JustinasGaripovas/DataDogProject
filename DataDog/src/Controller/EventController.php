<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends Controller
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
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('event_index');
        }
        $form->handleRequest($request);
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
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


}

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
     * @Route("/event", name="event_index")
     */
    public function index(EventRepository $repository, Request $request)
    {
        $events = $repository->findNewest();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function newEvent(Request $request)
    {
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
     * @Route("/event/edit/{event_id}", name="event_edit")
     */
    public function editEvent(Request $request, $event_id)
    {

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($event_id);


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
     * @Route("/event/delete/{event_id}", name="event_delete")
     */
    public function deleteEvent(Request $request, $event_id)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($event_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('event_index');
    }


}

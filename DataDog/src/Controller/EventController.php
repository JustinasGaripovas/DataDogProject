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
}
public function kazkokiaTaiFunkcija(){}
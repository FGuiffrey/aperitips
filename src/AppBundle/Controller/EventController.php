<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{
    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Event $event)
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/register/{id}", name="event_register")
     * @Method("POST")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Event                   $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request, Event $event): RedirectResponse
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('event_registration', $token)) {
            $event->addRegistered($this->getUser());

            $em = $this->get('doctrine')->getManager();
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('form.event.register_success', [], 'form'));
        }

        return $this->redirect($request->headers->get('referer'));
    }
}

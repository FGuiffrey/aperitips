<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Subject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->get('doctrine')->getManager();

        $event = $em->getRepository(Event::class)->findNextEvent();
        $subjects = $em->getRepository(Subject::class)->findAllPending();

        return $this->render('default/index.html.twig', [
            'event'    => $event,
            'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("/secure", name="secure")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function secureAction(): Response
    {
        return $this->render('default/secure.html.twig', []);
    }
}

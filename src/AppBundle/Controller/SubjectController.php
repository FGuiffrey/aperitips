<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subject;
use AppBundle\Form\SubjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Subject controller.
 *
 * @Route("subject")
 */
class SubjectController extends Controller
{
    /**
     * Creates a new subject entity.
     *
     * @Route("/new", name="subject_new")
     * @Method({"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setStatus(Subject::STATUS_PENDING);
            $subject->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('subject/new.html.twig', [
            'subject' => $subject,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/vote/{id}", name="subject_vote")
     * @Method("POST")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Subject                 $subject
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteAction(Request $request, Subject $subject)
    {
        $form = $this->createVoteForm($subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->addVote($this->getUser());

            $em = $this->get('doctrine')->getManager();
            $em->persist($subject);
            $em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createVoteForm(Subject $subject): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subject_vote', [
                'id' => $subject->getId(),
            ]))
            ->setMethod(Request::METHOD_POST)
            ->getForm();
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\Drank;
use AppBundle\Entity\Beverage;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Session controller.
 */
class SessionController extends Controller
{
    /**
     * Lists all session entities.
     *
     * @Route("/", name="session_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sessions = $em->getRepository('AppBundle:Session')->findByUserId($user);

        return $this->render('session/index.html.twig', array(
            'sessions' => $sessions,
        ));
    }

    /**
     * Creates a new session entity.
     *
     * @Route("session/new", name="session_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $session = new Session();
        $session->setUserId($user);

        $date = new \DateTime();

        $session->setStartTime($date);

        $em = $this->getDoctrine()->getManager();
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('beverage_index', array('session' => $session->getId()));

    }

    /**
     * Finds and displays a session entity.
     *
     * @Route("session/{id}", name="session_show")
     * @Method("GET")
     */
    public function showAction(Session $session)
    {
        $deleteForm = $this->createDeleteForm($session);

        return $this->render('session/show.html.twig', array(
            'session' => $session,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing session entity.
     *
     * @Route("/session{id}/edit", name="session_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Session $session)
    {
        $deleteForm = $this->createDeleteForm($session);
        $editForm = $this->createForm('AppBundle\Form\SessionType', $session);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('session_edit', array('id' => $session->getId()));
        }

        return $this->render('session/index.html.twig', array(
            'session' => $session,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a session entity.
     *
     * @Route("session/{id}", name="session_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Session $session)
    {
        $form = $this->createDeleteForm($session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($session);
            $em->flush();
        }

        return $this->redirectToRoute('session_index');
    }

    /**
     * Creates a form to delete a session entity.
     *
     * @param Session $session The session entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Session $session)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('session_delete', array('id' => $session->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

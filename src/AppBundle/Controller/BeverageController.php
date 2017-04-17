<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Beverage;
use AppBundle\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Beverage controller.
 *
 * @Route("beverage")
 */
class BeverageController extends Controller
{
    /**
     * Lists all beverage entities.
     *
     * @Route("/{session}", name="beverage_index")
     * @Method("GET")
     */
    public function indexAction(Session $session)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $session->setStartTime(new \DateTime());

        $em->persist($session);
        $em->flush();

        $beverages = $em->getRepository('AppBundle:Beverage')->findAll();

        return $this->render('beverage/index.html.twig', array(
            'beverages' => $beverages,
            'session' => $session,
        ));
    }

    /**
     * Creates a new beverage entity.
     *
     * @Route("/new", name="beverage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $beverage = new Beverage();
        $form = $this->createForm('AppBundle\Form\BeverageType', $beverage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beverage);
            $em->flush();

            return $this->redirectToRoute('beverage_show', array('id' => $beverage->getId()));
        }

        return $this->render('beverage/new.html.twig', array(
            'beverage' => $beverage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a beverage entity.
     *
     * @Route("/{id}", name="beverage_show")
     * @Method("GET")
     */
    public function showAction(Beverage $beverage)
    {
        $deleteForm = $this->createDeleteForm($beverage);

        return $this->render('beverage/show.html.twig', array(
            'beverage' => $beverage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing beverage entity.
     *
     * @Route("/{id}/edit", name="beverage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Beverage $beverage)
    {
        $deleteForm = $this->createDeleteForm($beverage);
        $editForm = $this->createForm('AppBundle\Form\BeverageType', $beverage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('beverage_edit', array('id' => $beverage->getId()));
        }

        return $this->render('beverage/edit.html.twig', array(
            'beverage' => $beverage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a beverage entity.
     *
     * @Route("/{id}", name="beverage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Beverage $beverage)
    {
        $form = $this->createDeleteForm($beverage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($beverage);
            $em->flush();
        }

        return $this->redirectToRoute('beverage_index');
    }

    /**
     * Add beverage and show info page
     *
     * @Route("/session/{sessionId}", name="beverage_session")
     * @Method("POST")
     */
    public function addBeverageToSession() {

    }

    /**
     * Creates a form to delete a beverage entity.
     *
     * @param Beverage $beverage The beverage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Beverage $beverage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('beverage_delete', array('id' => $beverage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

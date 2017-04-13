<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Drank;
use AppBundle\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Beverage;

class ConsumedController extends Controller
{
    /**
     * Creates a new consumed entity.
     *
     * @Route("drank/new/{beverage}/{session}", name="add_drink")
     * @Method({"GET", "POST"})
     */
    public function addDrink(Request $request, Beverage $beverage, Session $session)
    {

        $user = $this->getUser();
        $drank = new Drank();

        $drank->setAmount(0.5);
        $drank->setBeverageId($beverage);
        $drank->setSessionId($session);

        $em = $this->getDoctrine()->getManager();
        $em->persist($drank);
        $em->flush();

        return $this->render('drank/index.html.twig', array(
            'sessions' => $session,
            'beverage' => $beverage,
            'drank' => $drank,
        ));

    }

}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Drank;
use AppBundle\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Beverage;
use Symfony\Component\Validator\Constraints\Date;

class weightCall extends Controller
{

    public function addDrink(Request $request, Beverage $beverage, Session $session)
    {

        $begin = $_GET["start"];
        $end = $_GET["end"];

        var_dump($begin);die();
        $em = $this->getDoctrine()->getManager();

        $drank = $em->getRepository('AppBundle:Drank')->findById($id);

        $drank->setAmount($amount);

        $em->persist($drank);
        $em->flush();

    }

}
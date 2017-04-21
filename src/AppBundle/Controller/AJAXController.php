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

class AJAXController extends Controller
{

//    /**
//     * For AJAX call to put data in db
//     *
//     * @Route("drank/AJAX", name="AJAX_call")
//     * @Method({"GET", "POST"})
//     */
    public function AJAXCallAction(Request $request)
    {
        $data = $request->request->get('request');
        var_dump($data);
        die();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $em->persist();
        $em->flush();

    }
}

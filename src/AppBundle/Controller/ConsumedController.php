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


    /**
     * stops a new consumed entity.
     *
     * @Route("drank/stop/{session}", name="stop_drinking")
     * @Method({"GET", "POST"})
     */
    public function stopDrinking(Session $session)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $session->setEndTime(new \DateTime());

        $this->alcoholCalculator($session);

        $em->persist($session);
        $em->flush();

        return $this->render('drank/stop.html.twig', array(
            'session' => $session,
        ));
    }

    public function alcoholCalculator(Session $session) {

        //gram alcohol berekend met https://www.jellinek.nl/vraag-antwoord/hoeveel-alcohol-bevat-een-glas-bier-wijn-of-sterke-drank/

        $em = $this->getDoctrine()->getManager();


        $sessionUser = $session->getUserId();

        $totalAmountConsumed = 0;
        $totalDrinks = 0;
        $totalAlcohol = 0;
        $userAge = $sessionUser->getAge();
        $userGender = $sessionUser->getGender();
        $userWeight = $sessionUser->getWeight();
        $consumed = $em->getRepository('AppBundle:Drank')->findBySessionId($session);

        foreach($consumed as $drank) {
            $totalAmountConsumed += $drank->getAmount();
            $totalAlcohol += $drank->getBeverageId()->getAlcohol();

            $totalDrinks++;

        }

        $totalTime = $session->getStartTime()->diff($session->getEndTime());

        $days = $totalTime->days * 24 ;
        $days = $days * 60;
        $days = $days * 100;

        $hours = $totalTime->h * 60;
        $hours = $hours * 100;

        $minutes = $totalTime->m * 100;

        $seconds = $totalTime->s;

        $totalTimeInSeconds = $days + $hours + $minutes + $seconds;

        $totalTimeInMinutes = $totalTimeInSeconds / 100;

        $totalTimeInHours = $totalTimeInMinutes / 60;

        $totalAlcoholPercentage = $totalAlcohol / $totalDrinks;

        //[Alcohol consumed in grams / (Body weight in grams x r)] x 100. In this formula, “r” is the gender constant: r = 0.55 for females and 0.68 for males.[1]


    }
}

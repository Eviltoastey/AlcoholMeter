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

        $drank->setAmount(500);
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
     * For AJAX call to put data in db
     *
     * @Route("drank/AJAX", name="AJAX_call")
     * @Method({"GET", "POST"})
     */
    public function AJAXCallAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $data = $request->request->get('request');
            var_dump($data);die();
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            die();

            $em->persist();
            $em->flush();

            return 'yo';
        }

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

        $promille = $this->alcoholCalculator($session);

        $em->persist($session);
        $em->flush();

        return $this->render('drank/stop.html.twig', array(
            'session' => $session,
            'promille' => $promille,
            'effects' => $this->AlcoholEffects($promille),
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

        if($userGender == 0){
            $r = 0.68;
        }
        if($userGender == 1){
            $r = 0.55;
        }

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

        $totalAlcoholInGrams = $totalAlcoholPercentage * 0.008;

//        $totalAmountConsumedInML = $totalAmountConsumed * 1000;

        $totalAlcoholInGramsConsumed = $totalAlcoholInGrams * $totalAmountConsumed;

        $BACPercentage = $totalAlcoholInGramsConsumed / ($userWeight * 1000 * $r) * 100;

        $AlcoholInBlood = $BACPercentage - ($totalTimeInHours * 0.015);

        return $AlcoholInBlood;

    }

    function AlcoholEffects($promille) {

        $tip = 'U heeft teveel alcohol in uw bloed. U mag niet meer rijden.';
        $effects = array();

        if($promille <= 0){
            $tip = 'U heeft geen alcohol in uw bloed.';
            $effects = array();
            $promille = 0;
        }
        elseif($promille >= 0.001 && $promille <= 0.029) {
            $tip = 'U zit onder het maximum aantal promille. U moet nu stoppen met drinken als u de auto nog wil instappen.';
            $effects = array('Average individual appears normal');
        }
        elseif($promille >= 0.030 && $promille <= 0.059) {
            $effects = array('Mild euphoria','Relaxation','Joyousness', 'Talkactiveness', 'Decreased inhibition');
        }
        elseif($promille >= 0.060 && $promille <= 0.099) {
            $effects = array('Blunted feelings', 'Reduced sensitivity to pain', 'Euphoria', 'Disinhibition', 'Extraversion');
        }
        elseif($promille >= 0.1 && $promille <= 0.199) {
            $effects = array('Over-expression', 'Boisterousness', 'Possibility of nausea and vomiting');
        }
        elseif($promille >= 0.2 && $promille <= 0.299) {
            $effects = array('Nausea', 'Vomiting', 'Emotional swings', 'Anger or sadness', 'Partial loss of understanding', 'Impaired sensations', 'Decreased libido', 'Possibility of stupor');
        }
        elseif($promille >= 0.3 && $promille <= 0.399) {
            $effects = array('Stupor', 'Central nervous system depression', 'Loss of understanding', 'Lapses in and out of consciousness', 'Low possibility of death');
        }
        elseif($promille >= 0.4 && $promille <= 0.499) {
            $effects = array('Severe central nervous system depression', 'Coma', 'Possibility of death');
        }
        elseif($promille >= 0.5) {
            $effects = array('High possibility of death');
        }

        return array('tip' => $tip, 'effects' => $effects);

    }
}

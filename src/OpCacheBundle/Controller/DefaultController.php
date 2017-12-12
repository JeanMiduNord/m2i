<?php

namespace OpCacheBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/opc", name="accueil")
     */
    public function indexAction(Request $request)
    {
     /*   $paramGet = $request->query->get('reset');
        if ($paramGet != null and $paramGet=="oui"){
            opcache_reset();
        }*/
        $opc = new OpCache();

        return $this->render('OpCacheBundle:Default:index.html.twig',[
                   'opc' => $opc]);
    }

	/**
	 * @Route("/init", name="init")
	 */
	public function resetAction(Request $request)
	{
	    opcache_reset();
		return $this->redirectToRoute("accueil");
	}



    public function formatTailleAction($taille)
    {
        $ra = 0;
        if ($taille > 1048576) {
            $ra = sprintf("%.2f MB", $taille/1048576);
        } elseif ($taille > 1024) {
            $ra = sprintf("%.2f kB", $taille/1024);
        } else {
            $ra = sprintf("%d bytes", $taille);
        }
        return new Response($ra);
    }


}

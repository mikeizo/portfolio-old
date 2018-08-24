<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Config;


/**
 * Admin controller.
 *
 * @Route("admin")
 */
class AdminController extends Controller
{
	/**
	 * @Route("/")
	 */
	public function indexAction()
	{
		return $this->redirectToRoute('admin_projects_index');
	}


	/**
	 * @Route("/config/", name="admin_config_index")
	 * @Method({"GET", "POST"})
	 */
	public function configAction(Request $request)
	{
		$config = $this->getDoctrine()
			->getRepository(Config::class)
			->find(1);
		$form = $this->createForm('AppBundle\Form\ConfigType', $config);
		$form->handleRequest($request);
		$errors = false;

		// Form validation
		if($form->isSubmitted()){
			$validator = $this->get('validator');
			$errors = $validator->validate($config);
			$errorsString = false;
			if (count($errors) > 0) {
				$errorsString = (string) $errors;
			}
		}

		if($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($config);
			$em->flush();

			// Flash success message
			$this->addFlash("success", "Success! Configuration has been updated");
		}

		return $this->render('admin/config/index.html.twig', array(
			'config' 	=> $config,
			'form' 		=> $form->createView(),
			'errors' 	=> $errors,
		));
	}
}

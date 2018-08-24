<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\About;

/**
 * About controller.
 *
 * @Route("admin/about")
 */
class AboutController extends Controller
{
	/**
	 * Lists all about entities.
	 *
	 * @Route("/", name="admin_about_index")
	 * @Method("GET")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$abouts = $this->getDoctrine()
			->getRepository(About::class)
			->findBy(
				array(),
				array('yearFrom' => 'ASC')
			);
		$deleteForms = array();

		foreach ($abouts as $about) {
			$deleteForms[$about->getId()] = $this->createDeleteForm($about)->createView();
		}

		return $this->render('admin/about/index.html.twig', array(
			'abouts' 		=> $abouts,
			'deleteForms' 	=> $deleteForms,
		));
	}


	/**
	 * Creates a new about entity.
	 *
	 * @Route("/new", name="admin_about_new")
	 * @Method({"GET", "POST"})
	 */
	public function newAction(Request $request)
	{
		$about = new About();
		$form = $this->createForm('AppBundle\Form\AboutType', $about);
		$form->handleRequest($request);
		$errors = false;

		// Form validation
		if($form->isSubmitted()){
			$validator = $this->get('validator');
			$errors = $validator->validate($about);
			$errorsString = false;
			if (count($errors) > 0) {
				$errorsString = (string) $errors;
			}
		}

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($about);
			$em->flush();

			// Flash success message
			$this->addFlash("success", "Success! Record has been addded");

			return $this->redirectToRoute('admin_about_index', array('id' => $about->getId()));
		}

		return $this->render('admin/about/edit.html.twig', array(
			'about' 	=> $about,
			'form' 		=> $form->createView(),
			'errors' 	=> $errors,
		));
	}


	/**
	 * Displays a form to edit an existing about entity.
	 *
	 * @Route("/{id}/edit", name="admin_about_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, About $about)
	{
		$editForm = $this->createForm('AppBundle\Form\AboutType', $about);
		$editForm->handleRequest($request);

		// Form validation
		$validator = $this->get('validator');
		$errors = $validator->validate($about);
		$errorsString = false;
		if (count($errors) > 0) {
			$errorsString = (string) $errors;
		}

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			// Flash success message
			$this->addFlash("success", "Success! Timeline has been updated");

			return $this->redirectToRoute('admin_about_edit', array('id' => $about->getId()));
		}

		return $this->render('admin/about/edit.html.twig', array(
			'about' 	=> $about,
			'form'		=> $editForm->createView(),
			'errors' 	=> $errors
		));
	}


	/**
	 * Deletes a about entity.
	 *
	 * @Route("/{id}", name="admin_about_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, About $about)
	{
		$form = $this->createDeleteForm($about);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Flash success message
			$this->addFlash("success", "Success! Timeline has been deleted");

			$em = $this->getDoctrine()->getManager();
			$em->remove($about);
			$em->flush();
		}

		return $this->redirectToRoute('admin_about_index');
	}


	/**
	 * Creates a form to delete a about entity.
	 *
	 * @param About $about The about entity
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(About $about)
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('admin_about_delete', array('id' => $about->getId())))
			->setMethod('DELETE')
			->getForm()
		;
	}
}

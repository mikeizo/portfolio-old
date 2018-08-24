<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Skill controller.
 *
 * @Route("admin/contacts")
 */
class ContactController extends Controller
{
	/**
	 * Lists all contact entities.
	 *
	 * @Route("/", name="admin_contact_index")
	 * @Method({"GET", "POST"})
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$contacts = $em->getRepository('AppBundle:Contact')->findAll();
		$deleteForms = array();

		foreach ($contacts as $contact) {
			$deleteForms[$contact->getId()] = $this->createDeleteForm($contact)->createView();
		}

		return $this->render('admin/contact/index.html.twig', array(
			'contacts' => $contacts,
			'deleteForms' => $deleteForms,
		));
	}


	/**
	 * Deletes a contact entity.
	 *
	 * @Route("/{id}", name="admin_contact_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, Contact $contact)
	{
		$form = $this->createDeleteForm($contact);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Flash success message
			$this->addFlash("success", "Success! Contact has been deleted");

			$em = $this->getDoctrine()->getManager();
			$em->remove($contact);
			$em->flush();
		}

		return $this->redirectToRoute('admin_contact_index');
	}


	/**
	 * Creates a form to delete a contact entity.
	 *
	 * @param Contact $contact The contact entity
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Contact $contact)
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('admin_contact_delete', array('id' => $contact->getId())))
			->setMethod('DELETE')
			->getForm()
		;
	}

}

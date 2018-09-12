<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("admin/projects")
 */
class ProjectController extends Controller
{
	/**
	 * Lists all project entities.
	 *
	 * @Route("/", name="admin_projects_index")
	 * @Method("GET")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$projects = $this->getDoctrine()
			->getRepository(Project::class)
			->findBy(
				array(),
				array('position' => 'DESC')
			);
		$deleteForms = array();

		foreach ($projects as $project) {
			$deleteForms[$project->getId()] = $this->createDeleteForm($project)->createView();
		}

		return $this->render('admin/projects/index.html.twig', array(
			'projects' 		=> $projects,
			'deleteForms' 	=> $deleteForms,
		));
	}


	/**
	 * Creates a new project entity.
	 *
	 * @Route("/new", name="admin_projects_new")
	 * @Method({"GET", "POST"})
	 */
	public function newAction(Request $request)
	{
		$project = new Project();
		$form = $this->createForm('AppBundle\Form\ProjectType', $project);
		$form->handleRequest($request);
		$errors = false;

		// Form validation
		if($form->isSubmitted()){
			$validator = $this->get('validator');
			$errors = $validator->validate($project);
			$errorsString = false;
			if (count($errors) > 0) {
				$errorsString = (string) $errors;
			}
		}

		if($form->isSubmitted() && $form->isValid()) {
			// Generate Slug
			$slug = strtolower( str_replace(' ', '-', $request->request->get('appbundle_projects')['name']) );
			$project->setSlug($slug);

			// Get Max Position #
			$query = $this->getDoctrine()
				->getRepository(Project::class)
				->createQueryBuilder('p')
				->select('MAX(p.position)')
				->getQuery();
			$maxPosition = $query->getSingleResult();

			// Increase max position by 1
			if($maxPosition) {
				$project->setPosition($maxPosition[1] + 1);
			}
			else {
				$project->setPosition(1);
			}

			$logo = $project->getLogo();
			$image = $project->getImage();

			// Upload logo
			if($logo) {
				$logoName = $logo->getClientOriginalName();
				$logo->move($this->getParameter('upload_directory') . 'logos/', $logoName);
				$project->setLogo($logoName);
			}
			// Upload image
			if($image) {
				$imageName = $image->getClientOriginalName();
				$image->move($this->getParameter('upload_directory'), $imageName);
				$project->setImage($imageName);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($project);
			$em->flush();

			// Flash success message
			$this->addFlash("success", "Success! Project has been added");

			return $this->redirectToRoute('admin_projects_index');
		}		

		return $this->render('admin/projects/edit.html.twig', array(
			'project' 	=> $project,
			'form' 		=> $form->createView(),
			'errors'	=> $errors,
		));
	}


	/**
	 * Displays a form to edit an existing project entity.
	 *
	 * @Route("/{id}/edit", name="admin_projects_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, Project $project)
	{
		$editForm = $this->createForm('AppBundle\Form\ProjectType', $project);		
		$currentLogo = $project->getLogo();
		$currentImage = $project->getImage();
		$fileSystem = new Filesystem();
		$editForm->handleRequest($request);

		// Form validation
		$validator = $this->get('validator');
		$errors = $validator->validate($project);
		$errorsString = false;
		if (count($errors) > 0) {
			$errorsString = (string) $errors;
		}

		if($editForm->isSubmitted() && $editForm->isValid()) {
			$logo	= $project->getLogo();
			$image	= $project->getImage();

			// Upload logo
			if($logo) {
				$logoName = $logo->getClientOriginalName();

				// Remove old logo
				if($currentLogo && $currentLogo != $logoName) {
					$fileSystem->remove($this->getParameter('upload_directory') . 'logos/'. $currentLogo);
				}
				// Move logo to directory
				$logo->move($this->getParameter('upload_directory') . 'logos/', $logoName);
				$project->setLogo($logoName);
			}
			else {
				$project->setLogo($currentLogo);
			}

			// Upload image
			if($image) {
				$imageName = $image->getClientOriginalName();
				
				// Remove old image
				if($currentImage && $currentImage != $imageName) {
					$fileSystem->remove($this->getParameter('upload_directory') . $currentImage);
				}
				// Move image to directory
				$image->move($this->getParameter('upload_directory'), $imageName);
				$project->setImage($imageName);
			}
			else {
				$project->setImage($currentImage);
			}

			$this->getDoctrine()->getManager()->flush();

			// Flash success message
			$this->addFlash("success", "Success! Your project has been updated");

			return $this->redirectToRoute('admin_projects_edit', array('id' => $project->getId()));
		}
		else {
			// Restore values if errors
			$project->setLogo($currentLogo);
			$project->setImage($currentImage);
		}

		return $this->render('admin/projects/edit.html.twig', array(
			'project' 		=> $project,
			'form' 			=> $editForm->createView(),
			'errors' 		=> $errors,
			//'delete_form' => $deleteForm->createView(),
		));
	}


	/**
	 * Deletes a project entity.
	 *
	 * @Route("/{id}", name="admin_projects_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, Project $project)
	{
		$form 		= $this->createDeleteForm($project);
		$fileSystem = new Filesystem();
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$logo = $project->getLogo();
			$image = $project->getImage();
			$name = $project->getName();

			// Remove logo and images files
			if($logo) {
				$fileSystem->remove($this->getParameter('upload_directory') . 'logos/'. $logo);
			}
			if($image) {
				$fileSystem->remove($this->getParameter('upload_directory') . $image);
			}

			// Flash success message
			$this->addFlash("success", 'Success! Project "' . $name . '" has been deleted');

			$em = $this->getDoctrine()->getManager();
			$em->remove($project);
			$em->flush();
		}

		return $this->redirectToRoute('admin_projects_index');
	}


	/**
	 * Creates a form to delete a project entity.
	 *
	 * @param Projects $project The project entity
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Project $project)
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('admin_projects_delete', array('id' => $project->getId())))
			->setMethod('DELETE')
			->getForm()
		;
	}


	/**
	 * Sort project weights
	 *
	 * @Route("/sort")
	 * @Method("POST")
	 */
	public function sortAction(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();

		// Request POST data
		$positions = $request->request->get('positions');

		// Reverse array keys
		$reverse = array_reverse($positions);

		foreach($reverse as $key => $value) {
			// Update Project order
			$query = $entityManager->createQuery('
				UPDATE AppBundle:Project p
				SET p.position = :position
				WHERE p.id = :id
			')
			->setParameter('position', $key)
			->setParameter('id', $value);

			$projects = $query->getResult();
		}

		return new Response('', Response::HTTP_OK);
	}


	/**
	 * Remove images from projects
	 *
	 * @Route("/{id}/remove")
	 * @Method("POST")
	 */
	public function removeImageAction(Request $request, Project $project)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$fileSystem = new Filesystem();
		$type = $request->request->get('img');

		// Remove logo or images file
		if($type == 'logo') {
			$logo = $project->getLogo();
			$fileSystem->remove($this->getParameter('upload_directory') . 'logos/'. $logo);
			$project->setLogo(NULL);
		}
		if($type == 'image') {
			$image = $project->getImage();
			$fileSystem->remove($this->getParameter('upload_directory') . $image);
			$project->setImage(NULL);
		}

		$this->getDoctrine()->getManager()->flush();

		return new Response('', Response::HTTP_OK);
	}
}

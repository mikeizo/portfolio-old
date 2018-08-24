<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Config;
use AppBundle\Entity\Project;
use AppBundle\Entity\About;
use AppBundle\Entity\Skills;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactForm;
use AppBundle\Service\EmailService;

class HomeController extends Controller
{
	/**
	 * @Route("/", name="home_page")
	 */
	public function homeAction(Request $request)
	{
		$config = $this->getDoctrine()
			->getRepository(Config::class)
			->find(1);

		$projects = $this->getDoctrine()
			->getRepository(Project::class)
			->findBy(
				array(),
				array('position' => 'DESC')
			);

		$about = $this->getDoctrine()
			->getRepository(About::class)
			->findBy(
				array(),
				array('id' => 'ASC')
			);

		$skills = $this->getDoctrine()
			->getRepository(Skills::class)
			->findBy(
				array(),
				array('position' => 'ASC')
			);

		// Create Contact Form
		$contact = new Contact();
		$form = $this->createForm(ContactForm::class, $contact);
		$form->handleRequest($request);

		// Render page
		return $this->render('index.html.twig', array(
			'config' 	=> $config,
			'projects' 	=> $projects,
			'about'		=> $about,
			'skills'	=> $skills,
			'form'		=> $form->createView(),
		));
	}


	/**
	 * @Route("/contact")
	 * @Method("POST")
	 */
	public function contactAction(Request $request, EmailService $emailService)
	{
		$contact = new Contact();
		$form = $this->createForm('AppBundle\Form\ContactForm', $contact);
		$form->handleRequest($request);	
		$errors = false;	
		
		// Form validation
		if($form->isSubmitted()){
			$validator = $this->get('validator');
			$errors = $validator->validate($contact);
		}		

		// No validation errors
		if($form->isSubmitted() && $errors->count() == 0) {
			$contact_data = $form->getData();

			$config = $this->getDoctrine()
				->getRepository(Config::class)
				->find(1);

			// Log contact			
			$contact->setIp($_SERVER['REMOTE_ADDR']);
			$em = $this->getDoctrine()->getManager();
			$em->persist($contact);
			$em->flush();

			/*
			// Send email to admin
			$message = (new \Swift_Message('Website Contact Form'))
				->setFrom('no-reply@miketropea.com')
				->setTo($config->getEmail())
				->setBody(
					$this->renderView(
						'assets/email.html.twig',
						array('contact' => $contact_data)
					),
					'text/html'
			);
			$mailer->send($message);

			// Send email to user
			$message_thankyou = (new \Swift_Message('Thank You'))
				->setFrom('no-reply@miketropea.com')
				->setTo($contact_data->getEmail())
				->setBody(
					$this->renderView('assets/email-thankyou.html.twig'),
					'text/html'
			);
			$mailer->send($message_thankyou);
			*/

			// Send email to admin
			$message = $this->renderView('assets/email.html.twig', array('contact' => $contact_data));
			$emailService->email( $config->getEmail(), 'Website Contact Form', $message );

			// Send thankyou email to user
			$message_thankyou = $this->renderView('assets/email-thankyou.html.twig');
			$emailService->email( $contact_data->getEmail(), 'Thank You', $message_thankyou );

			return new JsonResponse(array('message' => 'Success!'), 200);

		}

		// Error messages
		foreach($errors as $error) {
			$error_messages[] = $error->getMessage();
		}

		// Return validation errors
		return new JsonResponse(array(
			'errors'	=> $error_messages
		), 400);
	}

}

<?php 

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller
{
	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
	{
		// Get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// Last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('admin/login.html.twig', array(
			'last_username' => $lastUsername,
			'error'			=> $error,
		));
	}


	/**
	 * @Route("/logout", name="logout")
	 */
	public function logoutAction()
	{
		throw new \Exception('this should not be reached!');
	}

}
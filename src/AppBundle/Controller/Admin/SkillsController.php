<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Skills;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Skill controller.
 *
 * @Route("admin/skills")
 */
class SkillsController extends Controller
{
	/**
	 * Lists all skill entities.
	 *
	 * @Route("/", name="admin_skills_index")
	 * @Method({"GET", "POST"})
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$skills = $em->getRepository('AppBundle:Skills')->findAll();
		// Get request
		$getSkills = $request->request->all();

		// Update skill
		if($getSkills) {
			foreach($getSkills as $skillKey => $skillData) {
				$skill = $em->getRepository(Skills::class)->find($skillKey);
				$skill->setPercent($skillData);
				$this->getDoctrine()->getManager()->flush();
			}
		}

		return $this->render('admin/skills/index.html.twig', array(
			'skills' => $skills,
		));
	}


}

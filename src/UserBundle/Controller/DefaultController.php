<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Doctrine\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;
use UserBundle\Form\Type\UserType;

class DefaultController extends Controller
{
	/**
	 *
	 * @Route("/profile", name="profile")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function profileAction(Request $request)
	{
		/** @var User $user */
		$user = $this->getUser();
		$oldPassword = $user->getPassword();

		$editForm = $this->createForm(UserType::class, $user);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			/** @var User $updatedUser */
			$updatedUser = $editForm->getData();
			/** @var UserManager $userManager */
			$userManager = $this->get('fos_user.user_manager');

			$user->setUsername($updatedUser->getUsername());
			$user->setEmail($updatedUser->getEmail());

			if ($updatedUser->getPassword()) {
				$user->setPlainPassword($updatedUser->getPassword());
			} else {
				$user->setPassword($oldPassword);
			}

			$userManager->updateUser($user);
			$this->addFlash('success', 'Your profile is successfully updated.');
			return $this->redirectToRoute('profile');
		}

		return $this->render('UserBundle:Default:profile.html.twig', array(
			'user' => $user,
			'edit_form' => $editForm->createView(),
		));
	}
}
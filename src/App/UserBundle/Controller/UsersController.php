<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\Users;
use App\UserBundle\Form\RegistrationType;
use App\UserBundle\Form\PreRegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
/**
 * User controller.
 *
 */
class UsersController extends BaseController
{
    public function preRegistrationAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm(PreRegistrationType::class, $user);
        $form->handleRequest($request);
        $data['form'] = $form->createView();
        $data['user'] = $user;
        $session = $request->getSession();
        $session->set('position', $user->getMerchandiser());
        if($form->isSubmitted())
        {
            return $this->redirectToRoute('app_user_registration');
        }
        return $this->render('AppUserBundle:Registration:preRegistration.html.twig', $data);
    }

    public function registerAction(Request $request)
    {
        $session = $request->getSession();
        $position = $session->get('position');
        $users = new Users();

        $users->setMerchandiser($users->getValueMerchandiseris($position));


        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        //$users = $userManager->createUser();
        $users->setEnabled(true);



        $event = new GetResponseUserEvent($users, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }



        $formUser = $this->createForm(RegistrationType::class, $users);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted()) {
            if ($formUser->isValid()) {
                $event = new FormEvent($formUser, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $username = $userManager->findUserBy(array('username' => $users->getUsername()));
                $userEmail = $userManager->findUserBy(array('email' => $users->getEmail()));
                if(isset($username) or isset($usermail))
                {
                    return $this->render('AppUserBundle:Registration:register.html.twig', array(
                        'form' => $formUser->createView(),
                        'user' => $users,
                    ));
                }
                $userManager->updateUser($users);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($users, $request, $response));

                return $response;
            }

            $event = new FormEvent($formUser, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('AppUserBundle:Registration:register.html.twig', array(
            'form' => $formUser->createView(),
        ));
    }

    /**
     * Creates a new user entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('App\UserBundle\Form\UsersType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('users/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('users/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('App\UserBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_edit', array('id' => $user->getId()));
        }

        return $this->render('users/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param Users $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

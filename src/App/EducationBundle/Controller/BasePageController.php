<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.09.16
 * Time: 13:10
 */

namespace App\EducationBundle\Controller;


use FOS\UserBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\EducationBundle\Entity\Product;
use Symfony\Component\Form\FormInterface;
//login
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
//register
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use App\UserBundle\Form\RegistrationType;
use App\UserBundle\Entity\Users;

use FOS\UserBundle\Form\Type\UsernameFormType;

use App\UserBundle\Reposytory\UsersRepository;

class BasePageController extends BaseController
{
    static $login;
    //static $register;

    public function basePageAction(Request $request)
    {
        $user = new Users();

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppEducationBundle:Product')->findAll();

        $logining = $this->getLoginAction($request);

        $logining['products'] = $products;

        $formRegistration = $this->createForm(RegistrationType::class, $user);
        $formRegistration->handleRequest($request);
        $logining['form'] = $this->getregisterAction($request);

        $template = $this->switcher($request);


        //$user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render("AppEducationBundle:Products:$template.html.twig", $logining
        );
    }



    public function previewAction(Request $request, $token)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppEducationBundle:Product')->findOneByToken($token);
        $data = $this->getloginAction($request);
        $data['form'] = $this->registerAction($request);
        $data['product'] = $product;
        return $this->render('AppEducationBundle:Products:preview.html.twig', $data);
    }

    public function getLoginAction(Request $request)
    {
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        );
    }

    public function getregisterAction(Request $request)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $form->createView();
    }

    protected function switcher($req)
    {
        $start = strpos($req, 'app_dev');
        $end = strpos($req, 'HTTP/1.1');
        $table = '/table/';
        $substr = substr($req, $start, $end-5);
        if(preg_match($table, $substr)){
            $template = 'index_table';
            return $template;
        }else{
            $template = 'index_content';
            return $template;
        }
    }
}
<?php

namespace App\EducationBundle\Controller;

use App\EducationBundle\Entity\Category;
use App\EducationBundle\Entity\Image;
use App\EducationBundle\Form\ImageType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Symfony\Bundle\AsseticBundle\DependencyInjection\Compiler\CheckClosureFilterPass;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Debug\Exception\OutOfMemoryException;
use Symfony\Component\Finder\Tests\Iterator\Iterator;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\EducationBundle\Form\ProductType;
use Symfony\Component\Form\Guess;
use Symfony\Component\Security\Core\Security;
use App\EducationBundle\Controller\BasePageController as BasePage;
use App\EducationBundle\Entity\Product;
use App\EducationBundle\Entity\User;
use App\EducationBundle\Controller\SecurityController;
use App\EducationBundle\Repository\ProductRepository;
use App\EducationBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintViolationList;
/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    public static $username;

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $product = new Product();
        $img = new Image();

        $product->getImage()->add($img);




        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $product->setName($this->getUser());



        if ($form->isSubmitted() && $form->isValid()) {
                foreach ($product->getImage() as $image) {
                    if($image->getImage()){
                        $fileName = $image->getImage();
                        $fileName = $this->uploadSwitcher($fileName, 'app.images_product_uploader');
                        $image->setImage($fileName);
                        //$fileName = $this->get('app.images_product_uploader')->uploadImages($fileName);

                        /*$imageFile = $image->getImage();
                        $fileName = md5(uniqid()).'.'.$imagefile->guessExtension();
                        $imagefile->move(
                            $this->getParameter('images_directory'),
                            $fileName
                        );
                        */
                        $image->setProduct($product);
                        $image->setImage($fileName);
                        $em->persist($image);
                    }
                }

            $typeCategory = $product->getType();
            $type = $em->getRepository('AppEducationBundle:Category')->findOneByCategory($typeCategory);

            $file = $product->getFile();


                $fileName = md5(uniqid()).'.'.$file->getClientOriginalName();
                $file->move(
                    $this->getParameter('audios_directory'),
                    $fileName
                );
                $productType = $product->getType();
                $productType = $em->getRepository('AppEducationBundle:Category')->findOneBy($productType);
                $id = $productType->getId();
                $product->setType($id);



            //$em->flush();
            //return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        $validator = $this->get('validator');

        $errors = $validator->validate($product);
        print_r($errors);
        $data['errors'] = null;
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;
            $data['errors'] = $errors;
            echo 'work';
        }

        $data['form'] = $form->createView();
        return $this->render('AppEducationBundle:Products:create.html.twig', $data);
    }

    /**
     * Finds and displays a Product entity.
     *
     */
    public function showAction(Product $product)
    {

        $em = $this->getDoctrine();
        $id = $product->getId();
        $product = $em->getRepository('AppEducationBundle:Product')->findById($id);
        $product = $product[0];
        $image = $em->getRepository('AppEducationBundle:Image')->findByProduct($id);

        $product_data['product'] = $product;
        $product_data['image'] = $image;
        return $this->render('AppEducationBundle:Products:show.html.twig', $product_data);
    }

    public function showProductsAction()
    {
        $user = $this->getUser();
        $em  = $this->getDoctrine();
        $products['products'] = $em->getRepository('AppEducationBundle:Product')->findByName("$user");
        return $this->render('AppEducationBundle:Products:show_products.html.twig', $products);
    }

    public function editProductAction($token)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppEducationBundle:Product')->findOneByToken($token);

        $img = new Image();
        $product->getImage()->add($img);

        if(!$product){
            throw $this->createNotFoundException('This product does not exist.');
        }

            foreach($product->getImage() as $image)
            {
                if($image->getImage())
                {
                    $this->overrideFiles(array(
                        'Image' => 'app.images_product_uploader'
                    ), $image, true);
                }

            }

        $products = $this->overrideFiles(array(
            'File' => 'pictures_directory',
            'ProductPicture' =>  'product_picture',
            'Logo' => 'image_logo'
        ), $product);

        $editForm = $this->createForm(ProductType::class, $products, array(
            'action' => $this->generateUrl('product_update', ['token' => $token]),
            'method' => 'PUT',
        ));

        return $this->render('AppEducationBundle:Products:edit.html.twig', array(
            'product'      => $product,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppEducationBundle:Product')->findByToken($token);
        if(!$product)
        {
            throw $this->createNotFoundException('This product does not exist.');
        }
        $editForm   = $this->createForm(new ProductType(), $product, [
            'action' => $this->generateUrl('product_update', ['token' => $token]),
            'method' => 'PUT'
        ]);
        if ($editForm->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_edit', array('token' => $token)));
        }

        return $this->render('AppEducationBundle:Product:edit.html.twig', array(
            'entity'      => $product,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public static function getCurrentUser()
    {
        return self::$username;
    }
}

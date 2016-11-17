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

use App\EducationBundle\Form\UserType;
use App\EducationBundle\Validator\Constraints as MimeTypeValidator;

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





        if ($form->isSubmitted() && $form->isValid())
        {
                foreach ($product->getImage() as $image)
                {
                    if($image->getImage())
                    {
                        $fileName = $image->getImage();
                        $fileName = $this->get('app.images_product_uploader')->uploadImages($fileName);
                        $image->setImage($fileName);

                        $image->setProduct($product);
                        $em->persist($image);
                    }
                }

            $typeCategory = $product->getType();
            $type = $em->getRepository('AppEducationBundle:Category')->findOneByCategory($typeCategory);


            $file = $product->getFile();
            $service = $this->fileType($file);
            $fileName = $this->get($service)->uploadImages($file);
            $product->setFile($fileName);

            $logo = $product->getLogo();
            $logoName = $this->get('app.image_logo_uploader')->uploadImages($logo);
            $product->setLogo($logoName);

            $pro_pic = $product->getProductPicture();
            $pro_picName = $this->get('app.product_picture_uploader')->uploadImages($pro_pic);
            $product->setProductPicture($pro_picName);

            $productType = $product->getType();






            $product->setName($this->getUser());
            $em->persist($product);

            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        $data['errors'] = null;

        $data['product'] = $product;

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

    public function fileType(file $file)
    {

        $fileType = $file->getClientMimeType();
        $types = array(
            '/audio/'  => 'app.audios_product_uploader',
            '/video/'  => 'app.videos_product_uploader',
            '/image/'  => 'app.pictures_product_uploader',
            '/text/'   => 'app.documents_product_uploader',
            '/pdf/'    => 'app.documents_product_uploader',
            '/msword/' => 'app.documents_product_uploader',
        );
        foreach($types as $type=>$parameter)
        {
            if(preg_match($type, $fileType))
            {
                return $parameter;
            }
        }
        $parameter = 'app.documents_product_uploader';
        return $parameter;

    }
    public static function getCurrentUser()
    {
        return self::$username;
    }
}

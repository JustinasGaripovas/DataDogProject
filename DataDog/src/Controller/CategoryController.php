<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */
    public function index(CategoryRepository $repository, Request $request)
    {
        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new/category", name="category_new")
     */
    public function newCategory(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/newCategory.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/subscribe", name="category_subscribe")
     */
    public function subscribeToCategory(Request $request, Security $security, CategoryRepository $categoryRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //  xml request validation
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $categoryId = $request->request->get('categoryId');
        } else {
            throw new NotFoundHttpException("Ajax only request.");
        }

        $category = $categoryRepository->findOneById($categoryId);;

        if ($category == null) {
            return new JsonResponse(['status' => '01', 'message' => 'Kategorija nerasta!']);
        }

        $user = $security->getToken()->getUser();

        if ($user->getSubscribedCategories()->contains($category)) {
            $user->removeSubscribedCategory($category);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['status'=>'00', 'message' => 'Sėkminga atšaukta premenuracija','button'=>"Subscribe"]);
        }else{
            $user->addSubscribedCategory($category);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['status'=>'00', 'message' => 'Sėkminga premenuracija','button'=>"Unsubscribe"]);
        }
    }

    /**
     * @Route("/category/{id}/edit", name="category_edit")
     */
    public function editCategory(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/{id}/delete", name="category_delete")
     */
    public function deleteCategory(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_index');
    }
}

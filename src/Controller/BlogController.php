<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    /**
     * @Route("/createblog", name="create_blog")
     */
    public function createBlog(Request  $request) {

        $blog = new Blog();

        $form = $this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);// ajout
            $em->flush();// mise a jour


            return $this->redirectToRoute('display_blog');

        }
        return $this->render('blog/createBlog.html.twig',['f'=>$form->createView()]);

    }


    /**
     * @Route("/displayBlog", name="display_blog")
     */
    public function displayBlog(): Response
    {
        $blogs = $this->getDoctrine()->getManager()->getRepository(Blog::class)->findAll();

        return $this->render('blog/displayBlog.html.twig',['b'=>$blogs]);


    }


    /**
     * @Route("/removeBlog/{id}", name="remove_blog")
     */
    public function removeBlog(Blog  $blog): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();

        return $this->redirectToRoute('display_blog');

    }
    /**
     * @Route("/updateblog/{id}", name="update_blog")
     */
    public function updateBlog(Request  $request,$id) {


        $blog = $this->getDoctrine()->getManager()->getRepository(Blog::class)->find($id);
        $form = $this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();// mise a jour


            return $this->redirectToRoute('display_blog');

        }
        return $this->render('blog/updateBlog.html.twig',['f'=>$form->createView()]);

    }


}

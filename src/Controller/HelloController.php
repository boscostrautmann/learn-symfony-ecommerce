<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{prenom?World}", name="hello")
     *
     * @param mixed $prenom
     */
    public function hello($prenom = 'World')
    {
        return $this->render('hello.html.twig', [
            'prenom' => $prenom,
        ]);
    }

    /**
     * @Route("/exemple", name="exemple")
     */
    public function exemple()
    {
        return $this->render('exemple.html.twig', [
            'age' => 33,
        ]);
    }
}

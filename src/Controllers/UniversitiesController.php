<?php

namespace Controllers;

use Repositories\UniversitiesRepository;
use Views\Renderer;

class UniversitiesController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct($connector)
    {
        $this->repository = new UniversitiesRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $universityData = $this->repository->findAll();
 
        return $this->twig->render('universities.html.twig', ['students' => $universityData]);
    }

    public function newAction()
    {
        if (isset($_POST['first_name'])) {
            $this->repository->insert(
                [
                    'university_name' => $_POST['university_name'],
                    'city'      => $_POST['city'],
                    'site_url'      => $_POST['site_url'],
                ]
            );
            return $this->indexAction();
        }
        return $this->twig->render('universities_form.html.twig',
            [
                'university_name' => '',
                'city' => '',
                'site_url' => '',
            ]
        );
    }

    public function editAction()
    {
        if (isset($_POST['university_name'])) {
            $this->repository->update(
                [
                    'university_name' => $_POST['university_name'],
                    'city'      => $_POST['city'],
                    'site_url'       => $_POST['site_url'],
                    'id'         => (int) $_GET['id'],
                ]
            );
            return $this->indexAction();
        }
        $universityData = $this->repository->find((int) $_GET['id']);
        return $this->twig->render('universities_form.html.twig',
            [
                'university_name' => $universityData['universityName'],
                'city' => $universityData['city'],
                'site_url' => $universityData['siteUrl'],
            ]
        );
    }

    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $this->repository->remove(['id' => $id]);
            return $this->indexAction();
        }
        return $this->twig->render('universities.html.twig', array('id' => $_GET['id']));
    }

    public function generateAction ()
    {
            if (isset($_POST['count'])) {
                $count = (int) $_POST['count'];
                $this->repository->generate($count);
                return $this->indexAction();
            }
            return $this->twig->render('universities_generate.html.twig');
    }
}
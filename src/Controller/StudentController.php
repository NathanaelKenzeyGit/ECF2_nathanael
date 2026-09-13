<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student_index')]
    public function index(StudentRepository $studentRepository): Response
    {

        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    #[Route('/student/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $student = new Student();
        
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $newfileName = uniqid() . '.' . $photoFile->guessExtension();
                dump($newfileName, $this->getParameter('photos_directory'));

                $photoFile->move($this->getParameter('photos_directory'), $newfileName);

                $student->setPhotoPath($newfileName);
            }
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('app_student_index');
        }

        return $this->render('student/form.html.twig', ['form' => $form]);
    }

    #[Route('/student/{id}/edit', name: 'app_student_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_student_index');
        }

        return $this->render('student/form.html.twig', ['form' => $form]);
    }
    #[Route('/student/{id}', name: 'app_student_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Student $student, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $student->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($student);
            $em->flush();
        }

        return $this->redirectToRoute('app_student_index');
    }
}

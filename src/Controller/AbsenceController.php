<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Student;
use App\Form\AbsenceType;
use App\Kernel;
use App\Repository\AbsenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AbsenceController extends AbstractController
{
    #[Route('/absence', name: 'app_absence_index')]
    public function index(AbsenceRepository $absenceRepository): Response
    {
        return $this->render('absence/index.html.twig', [
            'absences' => $absenceRepository->findBy([], ['absenceDate' => 'DESC']),
        ]);
    }

    #[Route('/absence/new/{id}', name: 'app_absence_new', requirements: ['id' => '\d+'], defaults: ['id' => null], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ?Student $student = null): Response
    {
        $absence = new Absence();

        if ($student) {
            $absence->setStudent($student);
        }

        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absence->setCreatedAt(new \DateTimeImmutable());
            $absence->setCreatedBy($this->getUser());

            $file = $form->get('proofFile')->getData();

            if ($file) {
                $newFileName = uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/documents',
                    $newFileName
                );

                $absence->setDocumentPath($newFileName);
            }

            $em->persist($absence);
            $em->flush();

            $this->addFlash('success', 'Absence enregistrée.');

            return $this->redirectToRoute('app_absence_index');
        }

        return $this->render('absence/form.html.twig', ['form' => $form]);
    }

    #[Route('/absence/{id}/edit', name: 'app_absence_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Absence $absence, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('proofFile')->getData();

            if ($file) {
                $newFileName = uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/documents',
                    $newFileName
                );

                $absence->setDocumentPath($newFileName);
            }

            $em->flush();

            $this->addFlash('success', 'Absence modifiée.');

            return $this->redirectToRoute('app_absence_index');
        }

        return $this->render('absence/form.html.twig', ['form' => $form]);
    }

    #[Route('/absence/{id}', name: 'app_absence_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Absence $absence, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $absence->getId(), $request->request->get('_token'))) {
            $em->remove($absence);
            $em->flush();

            $this->addFlash('success', 'Absence supprimée.');
        }

        return $this->redirectToRoute('app_absence_index');
    }
}

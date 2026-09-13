<?php

namespace App\Controller;

use App\Repository\AbsenceRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(StudentRepository $studentRepository, AbsenceRepository $absenceRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'students' => $studentRepository->findAll(),
            'totalAbsences' => $absenceRepository->count([]),
            'ranking' => $studentRepository->findRankedByAbsences(),
            'lostRevenue' => $absenceRepository->getLostRevenue(),
        ]);
    }
}

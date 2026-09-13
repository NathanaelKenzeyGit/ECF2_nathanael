<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\AbsenceReason;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('absenceDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de l'absence",
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'lastName',
                'label' => 'Stagiaire',
            ])
            ->add('reason', EntityType::class, [
                'class' => AbsenceReason::class,
                'choice_label' => 'label',
                'label' => 'Motif',
            ])
            ->add('proofFile', FileType::class, [
                'label' => 'Justificatif (PDF ou image)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de déposer un PDF, PNG, JPEG ou WebP.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Reclamation;
use Doctrine\DBAL\Types\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Reclamation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('commentaire', CKEditorType::class )
            ->add('datereclamation')

            ->add('typereclamation', ChoiceType::class,array(
                'choices'=>array(
                    'Voyage' => 'Voyage',
                    'Evenement' => 'Evenement',
                    'Hotel' => 'Hotel',
                )

            ))
            ->add('id',EntityType::class, [
                'empty_data' => ' ',
                'label' => 'id',
                'class' => Users::class,
                'label' => 'id',])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
    static public $types = array(
        'Voyage' => 'Voyage',
        'Evenement' => 'Evenement',
        'Hotel' => 'Hotel',
    );
}

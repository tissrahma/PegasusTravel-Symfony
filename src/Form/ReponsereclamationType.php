<?php

namespace App\Form;

use App\Entity\Reponsereclamation;

use Proxies\__CG__\App\Entity\Reclamation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponsereclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('reponse')
            ->add('numero',EntityType::class, [
                'empty_data' => ' ',
                'label' => 'numero',
                'class' => Reclamation::class,
                'choice_label' => 'numero',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponsereclamation::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Contact;
use ContactDTO;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mime\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email',EmailType::class)
            ->add('service', ChoiceType::class, [
                'choices'  => [
                    'Support' => 'support@cookingbaba.fr',
                    'Commerciaux' => 'sales@cookingbaba.fr',
                    'Comptabilité' => 'compta@cookingbaba.fr',
                ],
            ])
            ->add('subject')
            ->add('content')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
           ->add('save', SubmitType::class, [
                'label' => 'Envoyer le message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

<?php

namespace App\Form;

use Dom\Text;
use App\Entity\Recipe;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['empty_data' => ''])
            ->add('content',TextareaType::class,['empty_data' => ''])
            ->add('slug', TextType::class, [
                'required' => false,
                'help' => 'Laisser vide pour générer automatiquement le slug à partir du titre.',
            ])
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT,$this->attachTimeStamps(...))
        ;
    }

    public function attachTimeStamps(PostSubmitEvent $event): void
    {
        $now = new \DateTimeImmutable();
        $data = $event->getData();

        if( !$data instanceof Recipe) {
            return;
        }

        if ($data->getCreatedAt() === null) {
            $data->setCreatedAt($now);
        }

        $data->setUpdatedAt($now);
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
   
        if (!empty($data['title']) && empty($data['slug'])) {

            $slugger = new AsciiSlugger();
            $data['slug'] = $slugger->slug($data['title'])->lower();
            $event->setData($data);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

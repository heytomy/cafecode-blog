<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ArticleType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('slug', HiddenType::class, [
                'label' => 'Slug',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('featuredText', TextareaType::class, [
                'label' => 'Featured Text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Draft' => '1',
                    'Published' => '2'
                ]
            ])            
            ->add('featuredImage', FileType::class, [
                'label' => 'Featured Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file'
                ]
            ])
            ->add('author', EntityType::class, [
                'label' => 'Author',
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => User::class,
                'choice_label' => 'username',
                'data' => $this->security->getUser(),
                'disabled' => true,
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'Choisissez une categorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $data['slug'] = $data['title'];
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

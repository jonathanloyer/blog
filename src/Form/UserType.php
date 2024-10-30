<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, ['constraints' => [
            new Assert\NotBlank(),
            new Assert\Email(['message' => "Email incorrecte !"]),
        ]])
        ->add(
            'username',
            TextType::class,
            ['attr' => ['placeholder' => "Entrez votre nom"], "constraints" => [
                new Assert\NotBlank(['message' => 'Nom obligatoire!'])
            ]]
        )
            ->add(
                'password',
                PasswordType::class,
                ["label" => "mot de passe ", 'attr' => ['placeholder' => 'Entrez vôtre mot de passe'],
                "constraints" => [
                    new Assert\NotBlank(['message'=> 'mot de passe obligatoire']),
                    new Assert\Length(
                        [
                            "min"=> 6,
                            "max"=> 255,
                            'minMessage' => "Le mot de passe est trop court",
                            'maxMessage' => "Le mot de passe est trop long"

                        ]
                    )
                ]]
            )
            ->add(
                'Envoyer',
                SubmitType::class,
                ["attr" => ['class' => "button"]]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

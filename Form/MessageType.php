<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class MessageType extends AbstractType
{


    public function __construct(private readonly TokenStorageInterface $token)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $this->token->getToken()->getUser();
        $builder
            ->add('text', TextType::class)
            ->add('receiver', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) use ($user) {
                    return $userRepository->findUsersExcept($user);
                },
                'choice_label' => 'name',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'current_id' => 0
        ]);
    }
}

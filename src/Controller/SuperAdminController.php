<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\Voter\UserVoter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SuperAdminController extends AbstractController
{
    #[Route('/super_admin_approve_users', name: 'approve_users')]
    public function approveUsers(Request $request, EntityManagerInterface $em): Response
    {
        // Vérifiez si l'utilisateur est un super-admin
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('Seuls les super-admins peuvent accéder à cette page.');
        }

        // QueryBuilder pour récupérer les utilisateurs avec le rôle admin et non approuvés
        $repository = $em->getRepository(User::class);
        $queryBuilder = $repository->createQueryBuilder('u');

        $admins = $queryBuilder
            ->where('u.isApproved = :isApproved')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('isApproved', false)
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();

        $adminsChoices = [];
        foreach ($admins as $admin) {
            $adminsChoices[$admin->getFirstName() . ' ' . $admin->getLastName()] = $admin->getId();
        }

        $defaultData = ['approve' => false];
        $form = $this->createFormBuilder($defaultData)
            ->add('admins', ChoiceType::class, [
                'choices' => $adminsChoices,
                'label' => 'Sélectionnez un administrateur'
            ])
            ->add('approve', SubmitType::class, ['label' => 'Approuver'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $selectedAdminId = $data['admins'];

            $selectedAdmin = $em->getRepository(User::class)->find($selectedAdminId);
            if ($selectedAdmin) {
                // Avant d'approuver l'administrateur, vérifier si l'utilisateur connecté a le droit de le faire
                $this->denyAccessUnlessGranted(UserVoter::EDIT, $selectedAdmin);

                $selectedAdmin->setApproved(true);
                $em->persist($selectedAdmin);
                $em->flush();

                $this->addFlash('success', 'Administrateur approuvé avec succès !');
            } else {
                $this->addFlash('error', 'Erreur lors de l\'approbation de l\'administrateur.');
            }

            return $this->redirectToRoute('approve_users');
        }

        return $this->render('super_admin/approve.html.twig', ['admins' => $admins, 'form' => $form->createView()]);
    }
}

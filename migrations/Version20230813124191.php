<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813124191 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return 'Create admin';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $user = new User();
        $user->setEmail('admin@salerow.software');
        $user->setPassword($this->container->get('security.user_password_hasher')->hashPassword($user, 'Admin123!'));
        $user->setRoles(['ROLE_ADMIN']);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}

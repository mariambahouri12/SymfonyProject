<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528184406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE personal_program (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_11CA7EAFA76ED395 (user_id), INDEX IDX_11CA7EAFE934951A (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personal_program ADD CONSTRAINT FK_11CA7EAFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE personal_program ADD CONSTRAINT FK_11CA7EAFE934951A FOREIGN KEY (exercise_id) REFERENCES exercice_sur_poids_femme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personal_program DROP FOREIGN KEY FK_11CA7EAFA76ED395');
        $this->addSql('ALTER TABLE personal_program DROP FOREIGN KEY FK_11CA7EAFE934951A');
        $this->addSql('DROP TABLE personal_program');
    }
}

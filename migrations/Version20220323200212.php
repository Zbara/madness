<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323200212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fortune ADD users_id INT DEFAULT NULL, ADD finish INT NOT NULL');
        $this->addSql('ALTER TABLE fortune ADD CONSTRAINT FK_7F7BE60F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_7F7BE60F67B3B43D ON fortune (users_id)');
        $this->addSql('ALTER TABLE users ADD fortune_experince INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fortune DROP FOREIGN KEY FK_7F7BE60F67B3B43D');
        $this->addSql('DROP INDEX IDX_7F7BE60F67B3B43D ON fortune');
        $this->addSql('ALTER TABLE fortune DROP users_id, DROP finish');
        $this->addSql('ALTER TABLE users DROP fortune_experince');
    }
}
